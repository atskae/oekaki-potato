# spainter_all
These files are the result of extracting the `spainter_all.jar` file:

```
jar -xvf wacpoteto-1.6.7/oekaki/spainter_all.jar
```

and saving the decompiled `.class` files.

## Viewing Java source code
I had no luck with [VS Code's Java Decompiler](https://marketplace.visualstudio.com/items?itemName=dgileadi.java-decompiler&ssr=false#overview) but I'm probably just a ばか.

1. Download [Eclipse](https://www.eclipse.org/downloads/) (haven't used this since 140; this UI still looks like the 2000s)
2. Added the [Enhanced Class Decompiler](https://marketplace.eclipse.org/content/enhanced-class-decompiler#group-screenshots) from the Eclipse Marketplace (In Eclipse, go to `Help > Eclipse Marketplace`).
3. Set the installed decompiler as the default tool to open `.class` files. In Eclipse, go to `Preferences > General > Editors > File Associations`. For both `"*.class"` and `"*.class without source"` (in the top list) select `Class Decompiler Viewer` and click `default` button (in the botton list). Click `Apply and Close`. Then restart Eclipse.

You should be able to view any `.class` file and see the (approximate(?)) Java source code.

## spainter_all source code
No comments or anything... Oof.

The entry point seems to be `c/ShiPainter.class` according to [`shiBBS.php`](https://github.com/atskae/oekaki-potato/blob/master/wacpoteto-1.6.7/oekaki/shiBBS.php#L232).
