## Install

    wget https://get.symfony.com/cli/installer -O - | bash
    symfony new 

## Move symfony cli inside `bin` to get it under git & docker container 

    mv /root/.symfony/bin/symfony /var/www/app/bin/

## Check Requirements    

    bin/symfony check:req


## PHPSTORM

### Plugins

* symfony support
* php tool
* composer.json support


Enable symfony for this project : `settings > language & framework > symfony


### Fix phpstorm red color

File | Settings | Editor | Color Scheme | Console Colors
SET ANSI COLORS > GRAY to 255,255,255 