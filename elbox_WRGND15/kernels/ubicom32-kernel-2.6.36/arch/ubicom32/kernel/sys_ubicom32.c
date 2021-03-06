/*
 * arch/ubicom32/kernel/sys_ubicom32.c
 *   Ubicom32 architecture system call support implementation.
 *
 * (C) Copyright 2009, Ubicom, Inc.
 *
 * This file is part of the Ubicom32 Linux Kernel Port.
 *
 * The Ubicom32 Linux Kernel Port is free software: you can redistribute
 * it and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, either version 2 of the
 * License, or (at your option) any later version.
 *
 * The Ubicom32 Linux Kernel Port is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See
 * the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the Ubicom32 Linux Kernel Port.  If not,
 * see <http://www.gnu.org/licenses/>.
 *
 * Ubicom32 implementation derived from (with many thanks):
 *   arch/m68knommu
 *   arch/blackfin
 *   arch/parisc
 *
 * This file contains various random system calls that
 * have a non-standard calling sequence on the Linux/ubicom32
 * platform.
 */

#include <linux/module.h>
#include <linux/errno.h>
#include <linux/sched.h>
#include <linux/mm.h>
#include <linux/smp.h>
#include <linux/sem.h>
#include <linux/msg.h>
#include <linux/shm.h>
#include <linux/stat.h>
#include <linux/syscalls.h>
#include <linux/mman.h>
#include <linux/file.h>
#include <linux/utsname.h>
#include <linux/ipc.h>
#include <linux/fs.h>
#include <linux/uaccess.h>
#include <linux/unistd.h>

#include <asm/setup.h>
#include <asm/traps.h>
#include <asm/cacheflush.h>

#if (PAGE_SHIFT < 12)
#error "do_mmap2(): PAGE_SHIFT must be greater than 12"
#endif

/*
 * do_mmap2()
 *	Implement common interface code for mmap functions.
 */
static inline long do_mmap2(
	unsigned long addr, unsigned long len,
	unsigned long prot, unsigned long flags,
	unsigned long fd, unsigned long pgoff)
{
	int error = -EBADF;
	struct file *file = NULL;

	/*
	 * uClibc has a constant 12 bit page size.  Test
	 * that the pgoff is a multiple of the configured
	 * PAGE_SIZE and convert pgoff to our PAGE_SHIFT.
	 */
	if (pgoff & ((1 << (PAGE_SHIFT - 12)) - 1)) {
		return -EINVAL;
	}
	pgoff >>= PAGE_SHIFT - 12;

	flags &= ~(MAP_EXECUTABLE | MAP_DENYWRITE);
	if (!(flags & MAP_ANONYMOUS)) {
		file = fget(fd);
		if (!file) {
			goto out;
		}
	}

	down_write(&current->mm->mmap_sem);
	error = do_mmap_pgoff(file, addr, len, prot, flags, pgoff);
	up_write(&current->mm->mmap_sem);

	if (file) {
		fput(file);
	}
out:
	return error;
}

/*
 * sys_mmap2()
 *	System call linkage for sys_mmap2()
 */
asmlinkage long sys_mmap2(unsigned long addr, unsigned long len,
	unsigned long prot, unsigned long flags,
	unsigned long fd, unsigned long pgoff)
{
	return do_mmap2(addr, len, prot, flags, fd, pgoff);
}

struct mmap_arg_struct {
	unsigned long addr;
	unsigned long len;
	unsigned long prot;
	unsigned long flags;
	unsigned long fd;
	unsigned long offset;
};

/*
 * old_mmap()
 *	The old mmap interface mapped to do_mmap2()
 */
asmlinkage int old_mmap(struct mmap_arg_struct *arg)
{
	struct mmap_arg_struct a;
	int error = -EFAULT;

	if (copy_from_user(&a, arg, sizeof(a))) {
		goto out;
	}

	error = -EINVAL;
	if (a.offset & ~PAGE_MASK) {
		goto out;
	}

	/*
	 * Use a 12 bit (4K) shift to match do_mmap2()'s assumption that
	 * the caller is passing a 4K page size value.
	 */
	a.flags &= ~(MAP_EXECUTABLE | MAP_DENYWRITE);
	error = do_mmap2(a.addr, a.len, a.prot, a.flags, a.fd,
			 a.offset >> 12);
out:
	return error;
}

struct sel_arg_struct {
	unsigned long n;
	fd_set *inp, *outp, *exp;
	struct timeval *tvp;
};

/*
 * old_select()
 *	Map old select call to the new select call.
 */
asmlinkage int old_select(struct sel_arg_struct *arg)
{
	struct sel_arg_struct a;

	if (copy_from_user(&a, arg, sizeof(a))) {
		return -EFAULT;
	}

	/* 
	 * sys_select() does the appropriate kernel locking 
	 */
	return sys_select(a.n, a.inp, a.outp, a.exp, a.tvp);
}

/*
 * sys_ipc() 
 *	de-multiplexer for the SysV IPC calls.
 *
 * This is really horribly ugly.
 */
asmlinkage long sys_ipc(unsigned int call, int first, unsigned long second,
		       unsigned long third, void __user *ptr, long fifth)
{
	int version, ret;

	version = call >> 16; /* hack for backward compatibility */
	call &= 0xffff;

	if (call <= SEMCTL)
		switch (call) {
		case SEMOP:
			return sys_semop(first, (struct sembuf *)ptr, second);
		case SEMGET:
			return sys_semget(first, second, third);
		case SEMCTL: {
			union semun fourth;
			if (!ptr)
				return -EINVAL;
			if (get_user(fourth.__pad, (void **) ptr))
				return -EFAULT;
			return sys_semctl(first, second, third, fourth);
			}
		default:
			return -EINVAL;
		}
	if (call <= MSGCTL)
		switch (call) {
		case MSGSND:
			return sys_msgsnd(first, (struct msgbuf *) ptr,
					  second, third);
		case MSGRCV:
			switch (version) {
			case 0: {
				struct ipc_kludge tmp;
				if (!ptr)
					return -EINVAL;
				if (copy_from_user(&tmp,
						   (struct ipc_kludge *)ptr,
						   sizeof(tmp)))
					return -EFAULT;
				return sys_msgrcv(first, tmp.msgp, second,
						   tmp.msgtyp, third);
				}
			default:
				return sys_msgrcv(first,
						  (struct msgbuf *) ptr,
						  second, fifth, third);
			}
		case MSGGET:
			return sys_msgget((key_t) first, second);
		case MSGCTL:
			return sys_msgctl(first, second,
					   (struct msqid_ds *) ptr);
		default:
			return -EINVAL;
		}
	if (call <= SHMCTL)
		switch (call) {
		case SHMAT:
			switch (version) {
			default: {
				ulong raddr;
				ret = do_shmat(first, ptr, second, &raddr);
				if (ret)
					return ret;
				return put_user(raddr, (ulong __user *) third);
			}
			}
		case SHMDT:
			return sys_shmdt(ptr);
		case SHMGET:
			return sys_shmget(first, second, third);
		case SHMCTL:
			return sys_shmctl(first, second, ptr);
		default:
			return -ENOSYS;
		}

	return -EINVAL;
}

/* 
 * sys_cacheflush()
 *	flush (part of) the processor cache (or all of it :-)).
 */
asmlinkage int
sys_cacheflush(unsigned long addr, int scope, int cache, unsigned long len)
{
	flush_cache_all();
	return 0;
}

/*
 * sys_getpagesize()
 *	Return the system PAGE_SIZE.
 */
asmlinkage int sys_getpagesize(void)
{
	return PAGE_SIZE;
}
