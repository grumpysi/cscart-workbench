# CS-Cart Addon - Example addon

## Installation
- Download latest release zip file.
- Install zip file via the addon page in the CS-Cart control panel.
## Description
Addon description 

## Development Simlinks
From root of site create plugins folder with a folder called 'pluginname'
``` 
mkdir plugins/pluginname/
```

Simlink the main App addon folder
```
cd app/addons
ln -s ../../plugins/pluginname/app/addons/pluginname pluginname
```

Simlink the backend 'css' addon folder
```
cd design/backend/css/addons
ln -s ../../../../plugins/pluginname/design/backend/css/addons/pluginname pluginname
```

Simlink the backend 'media' addon folder
```
cd design/backend/media/images/addons
ln -s ../../../../../plugins/pluginname/design/backend/media/images/addons/pluginname pluginname
```

Simlink the backend 'templates' addon folder
```
cd design/backend/templates/addons
ln -s ../../../../plugins/pluginname/design/backend/templates/addons/pluginname pluginname
```

Simlink the 'var/langs/en' addon folder
```
cd var/langs/en/addons
ln -s ../../../../plugins/pluginname/var/langs/en/addons/pluginname.po pluginname.po
```
