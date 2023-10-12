function search(){
    var value = document.getElementById("search").value;
    var url = document.location.toString();
    var arrUrl = url.split("?");
    var obj = urlToObj(url);
    if(value.length != 0){
        if(objIsNull(obj)){
            value = "?search="+value;
        }else{
            if(obj["search"] != value){
                obj["search"] = value;
                delete obj.page;
            }
           value = "?"+urlEncode(obj).slice(1);
        }
    }else if(!objIsNull(obj)){
        delete obj.search;
        value = "?"+urlEncode(obj).slice(1);
    }
    window.location.href=arrUrl[0]+value
}

function advancedSearch(){
    var book_title = document.getElementById("book_title").value;
    var author = document.getElementById("author").value;
    var isbn = document.getElementById("isbn").value;
    var select_type_content = document.getElementById("select_type_content").value;
    var select_place_content = document.getElementById("select_place_content").value;
    var select_shelf_content = document.getElementById("select_shelf_content").value;
    var url = document.location.toString();
    var arrUrl = url.split("?");
    var obj = urlToObj(url);
    value = "";
    if(book_title.length != 0 ){
        value+= "&search="+book_title;
    }
    if(author.length != 0 ){
        value+= "&author="+author;
    }
    if(isbn.length != 0 ){
        value+= "&isbn="+isbn;
    }
    if(select_type_content.length != 0 ){
        value+= "&typeId="+select_type_content;
    }
    if(select_place_content.length != 0 ){
        value+= "&placeId="+select_place_content;
    }
    if(select_shelf_content.length != 0 ){
        value+= "&shelfId="+select_shelf_content;
    }


    str = value.substr(1);
    window.location.href=arrUrl[0] + "?" +value

}



function objIsNull(data){
    if (JSON.stringify(data) === '{}') {
        return true // 如果为空,返回false
    }
    return false // 如果不为空，则会执行到这一步，返回true
}


var urlEncode = function(param, key, encode) {
    if (param==null) return '';
    var paramStr = '';
    var t = typeof (param);
    if (t == 'string' || t == 'number' || t == 'boolean') {
        paramStr += '&' + key + '='  + ((encode==null||encode) ? encodeURIComponent(param) : param);
    } else {
        for (var i in param) {
            var k = key == null ? i : key + (param instanceof Array ? '[' + i + ']' : '.' + i)
            paramStr += urlEncode(param[i], k, encode)
        }
    }
    return paramStr;

}



function urlToObj(str){
    var obj = {};
    var arr1 = str.split("?");
    if(arr1.length != 1){
        var arr2 = arr1[1].split("&");
        for(var i=0 ; i < arr2.length; i++){
            var res = arr2[i].split("=");
            obj[res[0]] = res[1];
        }
    }
    return obj;
}


function isNull( str ){
    if ( str == "" ) return true;
}

function setData(id,data){
    document.getElementById(id).value=data;
}
