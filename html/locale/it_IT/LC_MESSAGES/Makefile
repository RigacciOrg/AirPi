.PHONY: all
all: default.mo cfg_default.mo

default.mo: default.po
	msgfmt -o default.mo default.po

cfg_default.mo: cfg_default.po
	msgfmt -o cfg_default.mo cfg_default.po
