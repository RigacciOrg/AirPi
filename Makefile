TARGET = airpi
PREFIX = $(DESTDIR)/usr/local
LIBDIR = $(PREFIX)/lib/$(TARGET)

AIRPI_CFG = /etc/airpi/airpi.cfg
CRON_AIRPI = /etc/cron.d/airpi
CRON_PMS5003 = /etc/cron.d/pms5003
SUDOERS = /etc/sudoers.d/airpi
BME280_STATUS = /var/run/bme280.status

LIB_FILES := $(shell cd lib; echo airpi-* bme280* pms5003* rrd-graph-*)
LIB_PYLIB := $(shell cd lib; echo Adafruit_BME280*)

.PHONY: all
all: ;

.PHONY: install
install: install-lib

.PHONY: install-lib
install-lib:
	install -m 755 -o root -g root -D -t $(LIBDIR) $(addprefix lib/, $(LIB_FILES))
	install -m 644 -o root -g root -D -t $(LIBDIR) $(addprefix lib/, $(LIB_PYLIB))
	python -m compileall $(LIBDIR)

.PHONY: install-config
install-config:
	test -e $(AIRPI_CFG)     || install -m 0640 -o root -g root examples/airpi.cfg $(AIRPI_CFG)
	test -e $(CRON_AIRPI)    || install -m 0644 -o root -g root examples/cron.airpi $(CRON_AIRPI)
	test -e $(CRON_PMS5003)  || install -m 0644 -o root -g root examples/cron.pms5003 $(CRON_PMS5003)
	test -e $(SUDOERS)       || install -m 0644 -o root -g root examples/sudoers.airpi $(SUDOERS)
	test -e $(BME280_STATUS) || install -m 0664 -o root -g snmp /dev/null $(BME280_STATUS)

.PHONY: uninstall
uninstall:
	-rm -f $(addprefix $(LIBDIR)/, $(LIB_FILES))
	-rm -f $(addprefix $(LIBDIR)/, $(LIB_PYLIB))
	-rm -f $(LIBDIR)/*.pyc
