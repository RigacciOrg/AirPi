SHELL=/bin/sh

BME280_STATUS=/var/run/bme280.status

	touch $(BME280_STATUS)
	chmod 0664 $(BME280_STATUS)
	chown root:snmp $(BME280_STATUS)
