#
# Makefile for DWC_common library
#
ifneq ($(KERNELRELEASE),)

#CPPFLAGS	+= -DDEBUG_MEMORY

#CPPFLAGS	+= -DDEBUG
CPPFLAGS	+= -DDWC_LINUX

#
# convert CPPFLAGS to kernel kbuild format
#
ccflags-y += $(CPPFLAGS)

obj-$(CONFIG_DWC_MODE_OTG)	:= dwc_common_port_lib.o
dwc_common_port_lib-objs	:= dwc_cc.o dwc_modpow.o dwc_dh.o \
				   dwc_crypto.o dwc_notifier.o \
				   dwc_common_linux.o dwc_mem.o dwc_ubicom.o

else


ifeq ($(DOXYGEN),)
DOXYGEN		:= $(DOXYGEN)
endif

default:
	$(MAKE) -C$(KDIR) M=$(PWD) modules

docs:	$(wildcard *.[hc]) doc/doxygen.cfg
	$(DOXYGEN) doc/doxygen.cfg

tags:	$(wildcard *.[hc])
	$(CTAGS) -e $(wildcard *.[hc]) $(wildcard linux/*.[hc]) $(wildcard $(KDIR)/include/linux/usb*.h)

endif

clean:
	rm -rf   *.o *.ko .*cmd *.mod.c .tmp_versions m*.order *.symvers

