function go()
{
	x = document.theform.nick.value;

	if(x.length < 11)
	{
	  return 0;
	}

	var displayURL = x.replace("http://", "");

	y = document.location.search.substring(1,11);
	y = "";

	document.theform.thelink1.value = "http://mangvn.org/protect/?" + x + "" + y;
	document.theform.thelink2.value = "<a href=\"http://mangvn.org/protect/?" + x + "" + y + "\">Link to " + displayURL + "</a>";
	document.theform.thelink3.value = "[url=http://mangvn.org/protect/?" + x  + "" + y + "]Link to " + displayURL + "[/url]";

	return false;
}
function generateCode(formName, displayIn)
{
	var script_path = "http://mangvn.org/protect/protect.js";
	var keywords = document.forms[formName].elements["keywords"].value;
	keywords = keywords.replace(" ", "");
	var keywords_array = new Array();

	var the_code = "";

	keyword_array = keywords.split(",");

	// check -> do some checks here, if wanted
	// ...

	// build the code
	the_code += "<script src=\"" +  script_path + "\" type=\"text/javascript\"></script>\n\n";

	// debug only
	//the_code += "&lt;!-- display stats (if you don't want stats the_code, just kick this and the next line) --&gt;\n";
	//the_code += "&lt;div&gt;anonyminized by anonym.to: &lt;span id=\"found_links\"&gt;&lt;/span&gt; links found; &lt;span id=\"anonyminized\"&gt;&lt;/span&gt; anonyminized&lt;/div&gt;\n\n";

	the_code += "<script type=\"text/javascript\"><!--\n";
	the_code += "protected_links = \"" + keyword_array.join(", ") + "\";\n\n";
	the_code += "auto_anonymize();\n";
	the_code += "//--></script>\n";

	// spit it out
	displayCode(displayIn, the_code);
}
function displayCode(displayIn, the_code)
{
	var the_element = document.getElementById(displayIn);

	the_element.value = "";
	the_element.value = the_code;
}
