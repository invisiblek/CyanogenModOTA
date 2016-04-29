#CyanogenModOTA
A simple OTA REST Server for CyanogenMod OTA Updater System Application

##How to use
Just <code>git clone</code> this repo and upload all the files to your preferred directory on your server.

You just have to be sure that is running PHP and Memcached.

After, upload all your ZIP files that you get after you build the ROM on the <code>_builds</code> directory.

##and after?
Just follow the rest of the tutorial on [Julian's personal blog post](http://blog.julianxhokaxhiu.com/entry/how-the-cm-ota-server-works-and-how-to-implement-and-use-ours) where I explain how to override the build server on your ROM.

##What about Debug?
[Simple script made for NodeJS](https://github.com/julianxhokaxhiu/CyanogenModOTAUnitTest) that you clone and use it.

##Do you support Delta updates?
- YES! Diff here: [hudson](https://github.com/androidarmv6/hudson/compare/master...ota)
- build_env/envsetup.sh exports keys: export OTA_PACKAGE_SIGNING_KEY=build_env/keys/platform
- For more information: build/target/product/security/README

Enjoy :)
