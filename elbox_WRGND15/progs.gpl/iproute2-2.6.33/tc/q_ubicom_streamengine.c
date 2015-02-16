/*
 * q_ubicom_streamengine.c	UBICOM STREAMENGINE.
 *
 *		This program is free software; you can redistribute it and/or
 *		modify it under the terms of the GNU General Public License
 *		as published by the Free Software Foundation; either version
 *		2 of the License, or (at your option) any later version.
 *
 * Authors:	Gareth Williams, <gareth.williams@ubicom.com>
 * Based on:	q_prio.c by Alexey Kuznetsov, <kuznet@ms2.inr.ac.ru>, many thanks.
 */

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <syslog.h>
#include <fcntl.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <string.h>

#include "utils.h"
#include "tc_util.h"
#include <linux/pkt_sched.h>

static void explain(void)
{
	fprintf(stderr, "Usage: ... prio bands NUMBER priomap P1 P2...[multiqueue]\n");
}

#define usage() return(-1)

/*
 * We support up to 256 bands (or priorities)
 */
#define STREAMENGINE_PRIO_MAX 255
#define STREAMENGINE_PRIO_BANDS 256

struct streamengine_prio_qopt
{
	int bands;					/* Number of bands */
	__u8 priomap[STREAMENGINE_PRIO_MAX + 1];	/* Maps a logical priority (array index) to a band (element value) */
};

static int streamengine_parse_opt(struct qdisc_util *qu, int argc, char **argv, struct nlmsghdr *n)
{
	int ok=0;
	int pmap_mode = 0;
	int idx = 0;
	struct streamengine_prio_qopt opt={3,{ 1, 2, 2, 2, 1, 2, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1 }};
	struct rtattr *nest;
	unsigned char mq = 0;

	while (argc > 0) {
		if (strcmp(*argv, "bands") == 0) {
			if (pmap_mode)
				explain();
			NEXT_ARG();
			if (get_integer(&opt.bands, *argv, 10)) {
				fprintf(stderr, "Illegal \"bands\"\n");
				return -1;
			}
			ok++;
		} else if (strcmp(*argv, "priomap") == 0) {
			if (pmap_mode) {
				fprintf(stderr, "Error: duplicate priomap\n");
				return -1;
			}
			pmap_mode = 1;
		} else if (strcmp(*argv, "multiqueue") == 0) {
			mq = 1;
		} else if (strcmp(*argv, "help") == 0) {
			explain();
			return -1;
		} else {
			unsigned band;
			if (!pmap_mode) {
				fprintf(stderr, "What is \"%s\"?\n", *argv);
				explain();
				return -1;
			}
			if (get_unsigned(&band, *argv, 10)) {
				fprintf(stderr, "Illegal \"priomap\" element\n");
				return -1;
			}
			if (band > opt.bands) {
				fprintf(stderr, "\"priomap\" element is out of bands\n");
				return -1;
			}
			if (idx > STREAMENGINE_PRIO_MAX) {
				fprintf(stderr, "\"priomap\" index > STREAMENGINE_PRIO_MAX=%u\n", STREAMENGINE_PRIO_MAX);
				return -1;
			}
			opt.priomap[idx++] = band;
		}
		argc--; argv++;
	}

/*
	if (pmap_mode) {
		for (; idx < TC_PRIO_MAX; idx++)
			opt.priomap[idx] = opt.priomap[TC_PRIO_BESTEFFORT];
	}
*/
	nest = addattr_nest_compat(n, 1024, TCA_OPTIONS, &opt, sizeof(opt));
	if (mq)
		addattr_l(n, 1024, TCA_PRIO_MQ, NULL, 0);
	addattr_nest_compat_end(n, nest);
	return 0;
}

int streamengine_print_opt(struct qdisc_util *qu, FILE *f, struct rtattr *opt)
{
	int i;
	struct streamengine_prio_qopt *qopt;
	struct rtattr *tb[STREAMENGINE_PRIO_MAX + 1];

	if (opt == NULL)
		return 0;

	if (parse_rtattr_nested_compat(tb, STREAMENGINE_PRIO_MAX, opt, qopt,
					sizeof(*qopt)))
                return -1;

	fprintf(f, "bands %u priomap ", qopt->bands);
	for (i=0; i <= STREAMENGINE_PRIO_MAX; i++) {
		fprintf(f, " %d", qopt->priomap[i]);
	}

	if (tb[TCA_PRIO_MQ])
		fprintf(f, " multiqueue: %s ",
		    *(unsigned char *)RTA_DATA(tb[TCA_PRIO_MQ]) ? "on" : "off");

	return 0;
}

struct qdisc_util streamengine_qdisc_util = {
	.id	 	= "streamengine",
	.parse_qopt	= streamengine_parse_opt,
	.print_qopt	= streamengine_print_opt,
};

