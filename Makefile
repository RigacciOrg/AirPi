TARGET = airpi
PREFIX = $(DESTDIR)/usr/local
LIBDIR = $(PREFIX)/lib/$(TARGET)
WWW_ROOT = /var/www/html

DIRECTORIES = /etc/airpi /etc/host-config /var/lib/airpi /var/www/html

AIRPI_CFG = /etc/airpi/airpi.cfg
WWW_CONFIG = $(WWW_ROOT)/config.php
WWW_INDEX = $(WWW_ROOT)/index.php
ETC_WWW_CONFIG = /etc/airpi/www_config.php
CRON_AIRPI = /etc/cron.d/airpi
CRON_PMS5003 = /etc/cron.d/pms5003
SUDOERS = /etc/sudoers.d/airpi
BME280_STATUS = /var/run/bme280.status

CFG_DIR = /etc/host-config
CFG_WEBCFG = /etc/host-config/webconfig.php
CFG_OPTIONS = /etc/host-config/options
CFG_PENDING = /etc/host-config/options-pending
CFG_REGEXP = /etc/host-config/options-regexp
CFG_IF_DHCP = /etc/network/interfaces.dhcp
CFG_IF_STATIC = /etc/network/interfaces.static
CFG_SBIN := $(shell cd webconfig/sbin; echo *)

LIB_FILES := $(shell cd lib; echo airpi-* bme280* calibration* pms5003* rrd-graph-*)
LIB_PYLIB := $(shell cd lib; echo Adafruit_BME280*)

# Check if an SNMP group exists.
ifneq ($(shell grep '^Debian-snmp:' /etc/group),)
SNMP_GROUP = Debian-snmp
else
ifneq ($(shell grep '^snmp:' /etc/group),)
SNMP_GROUP = snmp
else
SNMP_GROUP = root
endif
endif

.PHONY: all
all: ;

.PHONY: install
install: install-lib

.PHONY: install-lib
install-lib:
	install -m 755 -o root -g root -D -t $(LIBDIR) $(addprefix lib/, $(LIB_FILES))
	install -m 644 -o root -g root -D -t $(LIBDIR) $(addprefix lib/, $(LIB_PYLIB))
	python -m compileall $(LIBDIR)

.PHONY: install-directories
install-directories:
	install -m 0755 -o root -g root -d $(DIRECTORIES)

.PHONY: install-config
install-config: install-directories
	test -e $(AIRPI_CFG)     || install -m 0640 -o root -g www-data examples/airpi.cfg $(AIRPI_CFG)
	test -e $(CRON_AIRPI)    || install -m 0644 -o root -g root examples/cron.airpi $(CRON_AIRPI)
	test -e $(CRON_PMS5003)  || install -m 0644 -o root -g root examples/cron.pms5003 $(CRON_PMS5003)
	test -e $(SUDOERS)       || install -m 0440 -o root -g root examples/sudoers.airpi $(SUDOERS)
	test -e $(BME280_STATUS) || install -m 0664 -o root -g $(SNMP_GROUP) /dev/null $(BME280_STATUS)

.PHONY: install-webconfig
install-webconfig:
	test -e $(CFG_IF_DHCP)   || install -m 0644 -o root     -g root /dev/null $(CFG_IF_DHCP)
	test -e $(CFG_IF_STATIC) || install -m 0644 -o root     -g root /dev/null $(CFG_IF_STATIC)
	test -e $(CFG_WEBCFG)    || install -m 0640 -o www-data -g root /dev/null $(CFG_WEBCFG)
	test -e $(CFG_PENDING)   || install -m 0640 -o www-data -g root /dev/null $(CFG_PENDING)
	test -e $(CFG_OPTIONS)   || install -m 0640 -o www-data -g root webconfig/options $(CFG_OPTIONS)
	install -m 0644 -o root -g root webconfig/options-regexp $(CFG_REGEXP)
	rm -rf $(CFG_DIR)/templates && cp -pr webconfig/templates $(CFG_DIR)
	install -m 755 -o root -g root -D -t $(PREFIX)/sbin $(addprefix webconfig/sbin/, $(CFG_SBIN))

.PHONY: install-html
install-html: install-directories
	cp -pr html/* $(WWW_ROOT)
	chown -R root:root $(WWW_ROOT)
	test -e $(ETC_WWW_CONFIG) || install -m 0640 -o root -g www-data html/config-sample.php $(ETC_WWW_CONFIG)
	test -e $(WWW_CONFIG) || ln -s $(ETC_WWW_CONFIG) $(WWW_CONFIG)
	test -e $(WWW_INDEX) || ln -s station.php $(WWW_INDEX)

.PHONY: uninstall
uninstall:
	-rm -f $(addprefix $(LIBDIR)/, $(LIB_FILES))
	-rm -f $(addprefix $(LIBDIR)/, $(LIB_PYLIB))
	-rm -f $(LIBDIR)/*.pyc
