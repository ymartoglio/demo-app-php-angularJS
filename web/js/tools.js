/*************
* Prototypes *
*************/
Array.prototype.contains = function (element) {
    for (var i = 0; i < this.length ; i++) {
        if (this[i] === element) return true;
    }
    return false;
};

Array.prototype.remove = function (element) {
    for (var i = 0; i < this.length ; i++) {
        if (this[i] === element) this.splice(i,1);
    }
};

Array.prototype.removeAll = function (element) {
    while (this.length > 0) {
        this.pop();
    }
};

Array.prototype.getIds = function () {
    var ids = new Array();
    for (var i = 0; i < this.length ; i++) {
        if (this[i].id != undefined) {
            ids.push(this[i].id);
        }
    }
    return ids;
};

Array.prototype.getById = function (id) {
    for (var i = 0; i < this.length ; i++) {
        if (this[i].id == id) {
            return this[i];
        }
    }
    return null;
};

Array.prototype.match = function (prop,value) {
    for (var i = 0; i < this.length ; i++) {
        if (this[i][prop] != undefined && this[i][prop].toLowerCase().indexOf(value.toLowerCase()) != -1) return true;
    }
    return false;
};

Array.prototype.firstOnes = function (index) {
    var firstOnes = [];
    for (var i = 0; i < index ; i++) {
        firstOnes.push(this[i]);
    }
    return firstOnes;
};

Array.prototype.lastsFrom = function (index) {
    var lastsFrom = [];
    for (var i = index; i < this.length ; i++) {
        lastsFrom.push(this[i]);
    }
    return lastsFrom;
};