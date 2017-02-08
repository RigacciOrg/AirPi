TARGET = airpi
SHELL=/bin/sh

BME280_STATUS=/var/run/bme280.status

.PHONY: all
all: ;


	touch $(BME280_STATUS)
	chmod 0664 $(BME280_STATUS)
	chown root:snmp $(BME280_STATUS)

.PHONY: install-config
install-config:
	install -m 640 -o root -g root -D $(CFGFILE) /etc/$(CFGFILE)

.PHONY: uninstall
uninstall:
	-rm /etc/$(CFGFILE)
