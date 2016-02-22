# Varnish Cache Control

 * Manual: https://docs.typo3.org/typo3cms/extensions/vcc/
 * TER: https://typo3.org/extensions/repository/view/vcc
 * Git: https://git.typo3.org/TYPO3CMS/Extensions/vcc.git
 * Support: https://forge.typo3.org/projects/extension-vcc


For version specific information see [Changelog for Vcc](ChangeLog)


## Composer Support

VCC (stable) is available **from TYPO3 TER** and also available with composer ::

    {
        "repositories": [
            { "type": "composer", "url": "https://composer.typo3.org/" }
        ],
        .......
        "require": {
            "typo3/cms": "6.2.*",
            "typo3-ter/vcc": "*"
        }
    }

Or (unstable, don't blame me for bugs - but feel free to report bugs) directly **from Github** ::

    {
        "repositories": [
            { "type": "composer", "url": "https://composer.typo3.org/" },
            { "type": "vcs", "url": "https://github.com/mischka/TYPO3-extensions-vcc.git" },
        ],
        .......
        "require": {
            "typo3/cms": "6.2.*",
            "cpsitgmbh/vcc": "dev-features as 1.2.0"
        }
    }
    
## Varnish Example
    
    