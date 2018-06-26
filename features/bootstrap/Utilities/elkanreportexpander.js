//elkan behatformatter report is kind of buggy, and doesn't load all of it's content on first view, until you start expanding all the collapsed nodes
//use this to expand all collapsed nodes in the elkan beformatter report. copy/paste into chrome inspector console.
do {
    var list = document.querySelectorAll('div.jq-toggle:not(.jq-toggle-opened) h2,div.jq-toggle:not(.jq-toggle-opened) h3');
    for(var a of list) {
        a.click();
    }
}
while(list.length > 0);

