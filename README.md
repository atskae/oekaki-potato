# Oekaki Potato 🎨 🥔
Inspired by a snail mail exchange with one of my middle school art heros 🙂

My first goal is to learn how these Oekaki boards worked... and then figure out whether I can re-create some modern version.

## Directory Structure
* `doc/` My own notes
* `sptr1114/`
    * Downloaded from [Shi-painter Pro](http://hp.vector.co.jp/authors/VA016309/spainter/index_en.html).
    * `sptr114.zip` is the original (unmodified) version
* `wacpoteto-1.6.7/` modified contents of the latest version of `wacpoteto` (as of April 2021) from [NineChime](http://www.ninechime.com/products/).
    * `wacpoteto-1.6.7.zip` is the original (unmodified) file from NimeChime.

## Run Shi-painter Pro locally with Chrome
1. Download the [CheerpJ Applet Runner](https://chrome.google.com/webstore/detail/cheerpj-applet-runner/bbmolahhldcbngedljfadjlognfaaein/related) Chrome extension
    * This allows you to run Java applets without installing Java (cray-cray!)
    * Only works with a web server (can't just open an HTML file). More on this later.
2. Download [`sptr1114.zip`](http://hp.vector.co.jp/authors/VA016309/spainter/index_en.html) and extract the `.zip` file.
    * Or go to `sptr114/` directory in this repository
3. Download Python so that we can start a web server. More on this [here](https://developer.mozilla.org/en-US/docs/Learn/Common_questions/set_up_a_local_testing_server#running_a_simple_local_http_server).
4. Go to `sptr1114/` and start a web server (in Terminal):
```
cd sptr1114
python3 -m http.server
```
This will start a web server listening at `localhost:8000`.
5. Go to `http://localhost:8000/spainter_pro.html` in Chrome
6. Click on the `CheerpJ` Chrome Extension icon at the top right (icon should be orange)
    * `Applets detected` should be enabled (orange icon).
7. Click `Run Applets`. You should see the (nostalgic) Shi-painter Pro app!
[![Run Shi-painter Pro in Chrome](./doc/img/shi-painter-pro_in_chrome.png)]

## Links
* [NineChime](http://www.ninechime.com/products/)
    * This is where middle-school me downloaded some unverifed software and uploaded those files to some sketchy free web server to create an Oekaki board
* [Shi-painter Pro](http://hp.vector.co.jp/authors/VA016309/spainter/index_en.html)
    * This seems to be where the original download was for Shi-painter (and PaintBBS)

### HTML5 Ports
* [ChickenPaint](https://github.com/thenickdude/chickenpaint): ChibiPaint
* [PaintBBS](https://github.com/funige/neo)
