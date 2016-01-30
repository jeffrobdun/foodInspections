// JavaScript Document

var endpointUrl = './getResturants.php';

$(document).ajaxStart(function(){
	$("#loading").text("Loading...");
	$("#loading").css("display", "block");
	
});

$("#getResturants").click(function(){
	
	$.ajax({
		type: "POST",
		url: endpointUrl,
		statusCode: {
		    404: function() {
		    	alert( "page not found" );
		    }
		},
		data: $("#form").serialize(),
		success: function(data){
// 			$("#test").text(data);
			dataArray = JSON.parse(data);
			var table = buildResturantTable(dataArray, $("#results"));
			$("#results").css("display","block");
			$("#loading").css("display","none");
		},
		error: function(jqXHR, status, error){
			alert(status + " - " + error);
		}
	});	
	return false;
});

$("#letter").change(function(){
	$.ajax({
		type: "POST",
		url: endpointUrl,
		statusCode: {
		    404: function() {
		    	alert( "page not found" );
		    }
		},
		data: 'letter=' + $("#letter").val(),
		success: function(data){
			dataArray = JSON.parse(data);
			var table = buildResturantTable(dataArray, $("#results"));
			$("#results").css("display","block");
			$("#loading").css("display","none");
		},
		error: function(jqXHR, status, error){
			alert(status + " - " + error);
		}
	});	
	return false;
});

$("input[type=radio][name=resultMode]").click(function(event){
	if($(".radio[name=resultMode]:checked").val() == "current"){
		endpointUrl = './getResturants.php';
	}else{
		endpointUrl = './getEstablishmentsHistory.php';
	}
});

$(".radio").click(function(event){

	var image = event.target;
	var radio = $("#" + $(image).attr("for"));
	var checkedImageSrc = "https://cdn1.iconfinder.com/data/icons/material-core/20/radio-button-on-24.png";
	var uncheckedImageSrc = "https://cdn1.iconfinder.com/data/icons/material-core/20/radio-button-off-24.png";
	
	radio.click();
	var radioButtons = $("input[type=radio][name=" + $(radio).attr("name") + "]");
	for(var x=0;x<radioButtons.length;x++){
		if($(radioButtons[x]).prop("checked")){
			$("[for=" + $(radioButtons[x]).prop("id") + "]").prop("src", checkedImageSrc);
		}else{
			$("[for=" + $(radioButtons[x]).prop("id") + "]").prop("src", uncheckedImageSrc);
		}
	}
});

$(".checkbox").click(function(event){
	var image = event.target;
	var checkbox = $("#" + $(image).attr("for"));
	var checkedImageSrc = "https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/26/checked_checkbox.png";
	var uncheckedImageSrc = "https://cdn2.iconfinder.com/data/icons/windows-8-metro-style/26/unchecked_checkbox.png";
	
	if($(image).attr("src").includes("unchecked")){
		$(image).attr("src", checkedImageSrc);
		checkbox.click();
	}else{
		$(image).attr("src", uncheckedImageSrc);
		checkbox.click();
	}
});

function buildResturantTable(data, table){
	var tableString = '';
	
	var tableHeaders = table.find("th");
	var template = '';
	var templateString = '';
	
	for(var i=0;i<data.length;i++){
		tableString += '<tr>';
		for(var j=0;j<tableHeaders.length;j++){
			template = templates[$(tableHeaders[j]).attr('data-column') + 'Template'];
			
			if(template){
				templateString = template.replace("{value}", data[i][$(tableHeaders[j]).attr('data-column')]);
				tableString += '<td class="' + $(tableHeaders[j]).attr('data-column') + '">' + templateString + '</td>';
			}else{
				tableString += '<td class="' + $(tableHeaders[j]).attr('data-column') + '">' + data[i][$(tableHeaders[j]).attr('data-column')] + '</td>';
			}
		}
		tableString += '</tr>';
	}
	
	//http://www1.gnb.ca/0601/images/public_green.gif
	
	table.find('tbody').html(tableString);
	
	return tableString;
}