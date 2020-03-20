const puppeteer = require('puppeteer');


(async () => {

    const fs = require('fs');
    const path = require('path');
    const watch = require('node-watch');

    var Data = fs.readFileSync(path.join(__dirname, "js/index.js"), "utf-8");

    const browser = await puppeteer.launch({
        headless: false,
        devtools: true,
        executablePath: 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
        ignoreDefaultArgs: ['--disable-extensions'],
        handleSIGINT: false,
        defaultViewport:null,
        userDataDir: './userdata',
    });

    const page = await browser.newPage();

    var gotoURL = process.argv[3]?process.argv[3]:'http://localhost/';
    console.log( gotoURL );
    await page.goto(gotoURL);

    // await page.evaluate(async (Data) => {
    //     eval(Data);

    // }, Data);
    // await browser.close();




       //var folder       = "assets/";
       var folder      = process.argv[2]?process.argv[2]+"/":"assets/";
    watch(path.join(__dirname, folder ) , { encoding: 'utf-8', persistent:true, recursive:true }, function (eventType, filename ) {
        ext = filename.split(".").pop();
    
        if( ext == "css" ){
            var cssData = fs.readFileSync( path.join(  filename ), "utf-8");
            var pluginfolder  = __dirname.split("\\").pop();
            totalPath =  path.join(  filename ).split(pluginfolder).pop();
            cssUrl = path.join(pluginfolder, totalPath).split("\\").join("\/");

            function injectCSS( page, path, cssUrl ){

                 page.evaluate( async (cssUrl) =>{
                    el = document.querySelector("link[href*='"+cssUrl+"']");
                    if(el){
                        el.parentNode.removeChild(el);
                    }
                   
                }, cssUrl );

                page.addStyleTag({ path: path} );
            }
             injectCSS( page, path.join(  filename ), cssUrl );

        }else if( ext == "js" ){

            var jsData = fs.readFileSync( path.join(  filename ), "utf-8");

            function runJS(jsData){

                 page.evaluate( async (jsData) =>{
                    eval(jsData);
                }, jsData );
            }
            runJS(jsData);
        }   
    });
      
})();