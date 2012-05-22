#!/bin/bash

# Upgrades PEAR.
pear upgrade PEAR

# PHPUnit.
pear config-set auto_discover 1
pear channel-discover pear.phpunit.de
pear install phpunit/PHPUnit

# PPW, PHPCPD, PHPLOC.
pear channel-discover components.ez.no

# PPW.
pear install phpunit/ppw

# PHPCPD.
pear install phpunit/phpcpd

# PHPLOC.
pear install phpunit/phploc

# PDepend.
pear channel-discover pear.pdepend.org
pear install pdepend/PHP_Depend-beta

# PHPMD (depends on PDepend)
pear channel-discover pear.phpmd.org
pear install --alldeps phpmd/PHP_PMD

# PHP CodeSniffer.
pear install pear/PHP_CodeSniffer

exit 0;