
function doSearch() {
    var searchTerms = document.getElementById("search").value.split(",");

    for (var i = searchTerms.length - 1; i >= 0; i--) {
        searchTerms[i] = searchTerms[i].trim().toLowerCase();
    };

    console.log(searchTerms);

    // Read in index.xml file

    var xmlFile = "../index.xml";
    var xmlDoc;

    if(typeof window.DOMParser != "undefined") {
        xmlhttp=new XMLHttpRequest();
        xmlhttp.open("GET",xmlFile,false);
        if (xmlhttp.overrideMimeType){
            xmlhttp.overrideMimeType('text/xml');
        }
        xmlhttp.send();
        xmlDoc=xmlhttp.responseXML;
    }
    else{
        xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
        xmlDoc.async="false";
        xmlDoc.load(xmlFile);
    }

    var items = xmlDoc.getElementsByTagName("item");
    var searchResults = document.getElementById('search-results');


    // Clear any existing search results from the last search
    while (searchResults.firstChild) {
        searchResults.removeChild(searchResults.firstChild);
    }   

    
    // console.log("Found " + items.length + " items to search");

    for (var i = 0; i < items.length; i++) {
        var itemFound = false;
        var item = items[i];

        var itemTitle = item.getElementsByTagName("title")[0].childNodes[0].nodeValue;
        var itemDescription = item.getElementsByTagName("description")[0].childNodes[0].nodeValue;
        var itemUrl = item.getElementsByTagName("link")[0].childNodes[0].nodeValue;

        for (var j = 0; j < searchTerms.length; j++) {
            if (itemTitle.toLowerCase().includes(searchTerms[j]) || itemDescription.toLowerCase().includes(searchTerms[j])) {
                itemFound = true;
                break;
            }
        };

        if (itemFound) {
            // console.log("item title: " + itemTitle);
            // console.log("item description: " + itemDescription);  

            appendSearchResult(itemTitle, itemDescription, itemUrl);

        }
        
    };
}


function appendSearchResult(title, description, url) {
    var searchResults = document.getElementById('search-results');

    var foundItemNode = document.createElement("div");
    var foundItemLink = document.createElement("a");
    foundItemLink.setAttribute("href", url);
    foundItemLink.appendChild(document.createTextNode(title))
    foundItemNode.appendChild(foundItemLink);


    // var foundItemText = document.createTextNode(title);
    // foundItemNode.appendChild(foundItemText);

    foundItemNode.className = 'found-item';

    // var foundItemDescription = document.createElement("p");
    // foundItemDescription.className = 'post-summary';
    // foundItemDescription.appendChild(document.createTextNode(description));

    // foundItemNode.appendChild(foundItemDescription);
    searchResults.appendChild(foundItemNode);
}



