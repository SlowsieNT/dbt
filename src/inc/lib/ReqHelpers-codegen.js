// Were you expecting modern js?
// Learn to expect nothing so you don't get disappointed ever again.
function GenerateFunc(aType, aFName, aArgc, aFuncArgs, aFuncBody){
	var fout = "function " + aFName;
	if (!aArgc || aArgc < 1)
		aArgc = 101;
	for (var i = 1, r1 = [], r2 = []; i < aArgc; i++) {
		r1.push("$aKey"+i+" = \"\", &$aOutVar"+i+" = null"),
		r2.push("$aKey"+i+", $aOutVar"+i);
	}
	fout += "(" + aFuncArgs(r1.join(", "))+") {\r\n";
	fout += aFuncBody(r2.join(", "));
	fout += "\r\n}";
	return {
		a: fout,
		b: r1,
		c: r2
	};
}

// main function
copy(GenerateFunc(0, "Request_GPFSC_Get33", 0, function args(aArgsStr) {
	return "$aType = 0, " + aArgsStr + "";
}, function body(aArgsStr2) {
	return "";
}).a);

// Create all getters
// Just don't forget to edit comment for $_FILE,
// it should be $_FILES as comment, not an issue since it's comment
var output = [];
var types = "Get,Post,File,Session,Cookie".split(",");
for (var i in types) {
	var fn = "G" + types[i] + "33";
	output.push(
		"// Getter for $_" + types[i].toUpperCase() + "\r\n"+
		GenerateFunc(i, fn, 0, function args(aArgsStr) {
			return "" + aArgsStr + "";
		}, function body(aArgsStr2) {
			return (
				//"\t\r\n"+
				"\treturn Request_GPFSC_Get33("+i+", "+aArgsStr2+");"
			);
		}).a
	);
}
copy(output.join("\r\n"));
