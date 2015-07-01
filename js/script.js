	/*
	* Add edit delete rows dynamically using jquery and php
	* http://www.amitpatil.me/
	*
	* @version
	* 2.0 (4/19/2014)
	* 
	* @copyright
	* Copyright (C) 2014-2015 
	*
	* @Auther
	* Amit Patil
	* Maharashtra (India)
	*
	* @license
	* This file is part of Add edit delete rows dynamically using jquery and php.
	* 
	* Add edit delete rows dynamically using jquery and php is freeware script. you can redistribute it and/or 
	* modify it under the terms of the GNU Lesser General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* (at your option) any later version.
	* 
	* Add edit delete rows dynamically using jquery and php is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	* 
	* You should have received a copy of the GNU General Public License
	* along with this script.  If not, see <http://www.gnu.org/copyleft/lesser.html>.
	*/

// init variables

var trcopy;
var editing = 0;
var tdediting = 0;
var editingtrid = 0;
var editingtdcol = 0;
var inputs = ':checked,:selected,:text,textarea,select';

$(document).ready(function(){

	// set images for edit and delete 
	$(".eimage").attr("src",editImage);
	$(".dimage").attr("src",deleteImage);


	// init table
	blankrow = '<tr valign="top" class="inputform"><td></td>';
	for(i=0;i<columns.length;i++){
		// Create input element as per the definition
		input = createInput(i,'');
		blankrow += '<td class="ajaxReq">'+input+'</td>';
	}
	blankrow += '<td><a href="javascript:;" class="'+savebutton+'"><img src=" "><span class="glyphicon glyphicon-save"></span></a></td></tr>';

	// append blank row at the end of table
	$("."+table).append(blankrow);

	// Delete record
	$(document).on("click","."+deletebutton,function(){
		var id = $(this).attr("id");
		if(id){
			if(confirm("Do you really want to delete record ?"))
				ajax("rid="+id,"del");
		}
	});

	// Add new record
	$("."+savebutton).on("click",function(){
		var validation = 1;

		var $inputs =
		$(document).find("."+table).find(inputs).filter(function() {
			// check if input element is blank ??
			if($.trim( this.value ) == ""){
				$(this).addClass("error");
				validation = 0;
			}else{
				$(this).addClass("success");
			}
			return $.trim( this.value );
		});

		var array = $inputs.map(function(){
			//console.log(this.value);
			//console.log(this);
			return this.value;
		}).get();

		var serialized = $inputs.serialize();
		alert(serialized);
		if(validation == 1){
			ajax(serialized,"save");
		}
	});

	// Add new record
	$(document).on("click","."+editbutton,function(){
		var id = $(this).attr("id");
		if(id && editing == 0 && tdediting == 0){
			// hide editing row, for the time being
			$("."+table+" tr:last-child").fadeOut("fast");
						
			var html;
			html += "<td>"+$("."+table+" tr[id="+id+"] td:first-child").html()+"</td>";
			for(i=0;i<columns.length;i++){
				// fetch value inside the TD and place as VALUE in input field
				var val = $(document).find("."+table+" tr[id="+id+"] td[class='"+columns[i]+"']").html();
				input = createInput(i,val);
				html +='<td>'+input+'</td>';
			}
			html += '<td><a href="javascript:;" id="'+id+'" class="'+updatebutton+'"><img src=""><img src="" class="eimage"> <span class="glyphicon glyphicon-save"></span></a> <a href="javascript:;" id="'+id+'" class="'+cancelbutton+'"><img src="" > <span class="glyphicon glyphicon-remove"></span></a></td>';
			
			// Before replacing the TR contents, make a copy so when user clicks on 
			trcopy = $("."+table+" tr[id="+id+"]").html();
			$("."+table+" tr[id="+id+"]").html(html);	
			
			// set editing flag
			editing = 1;
		}
	});

	$(document).on("click","."+cancelbutton,function(){
		var id = $(this).attr("id");
		$("."+table+" tr[id='"+id+"']").html(trcopy);
		$("."+table+" tr:last-child").fadeIn("fast");
		editing = 0;
	});

	$(document).on("click","."+updatebutton,function(){
		id = $(this).attr("id");
		var $inputs =
		$(document).find("."+table).find(inputs).filter(function() {
			return $.trim( this.value );
		});

		var array = $inputs.map(function(){
			console.log(this.value);
			return this.value;
		}).get();
		serialized = $inputs.serialize();
		ajax(serialized+"&rid="+id,"update");
		// clear editing flag
		editing = 0;
	});

	// td doubleclick event
	$(document).on("dblclick","."+table+" td",function(e){
		// check if any other TD is in editing mode ? If so then dont show editing box
		//alert(tdediting+"==="+editing);
		var isEditingform = $(this).closest("tr").attr("class");
		if(tdediting == 0 && editing == 0 && isEditingform != "inputform"){
			editingtrid = $(this).closest('tr').attr("id");
			editingtdcol = $(this).attr("class");
			var text = $(this).html();
			var tr = $(this).parent();
			var tbody = tr.parent();
			for (var i = 1; i < tr.children().length-1; i++) {
				if (tr.children().get(i) == this) {

					var column = i;
					break;
				}
			}
			
			// decrement column value by one to avoid sr no column
			column--; 
			//alert(column+"==="+placeholder[column]);
			if(column <= columns.length){
				var text = $(this).html();
				//alert(text);
				input = createInput(column,text);
				$(this).html(input);
				$(this).find(inputs).focus();
				tdediting = 1;
			}
		}
	});
	
	// td lost focus event
	
	$(document).on("blur","."+table+" td",function(e){
		if(tdediting == 1){
			var newval = $("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").find(inputs).val();
			ajax(editingtdcol+"="+newval+"&rid="+editingtrid,"updatetd");
		}
	});
	
});

createInput = function(i,str){
	str = typeof str !== 'undefined' ? str : null;
	//alert(str);
	if(inputType[i] == "text"){
		input = '<input type='+inputType[i]+' name='+columns[i]+' placeholder="'+placeholder[i]+'" value='+str+' >';
	}else if(inputType[i] == "textarea"){
		input = '<textarea name='+columns[i]+' placeholder="'+placeholder[i]+'">'+str+'</textarea>';
	}
	else if(inputType[i] == "select"){
		input = '<select name='+columns[i]+'>';
		for(i=0;i<selectOpt.length;i++){
			//console.log(selectOpt[i]);
			selected = "";
			if(str == selectOpt[i])
				selected = "selected";
			input += '<option value="'+selectOpt[i]+'" '+selected+'>'+selectOpt[i]+'</option>';
		}
		input += '</select>';
		//console.log(str);
	}
	return input;
}

ajax = function (params,action){
	$.ajax({
		type: "POST", 
		url: "ajax.php", 
		data : params+"&action="+action,
		dataType: "json",
		success: function(response){
		  switch(action){
			case "save":
				var seclastRow = $("."+table+" tr").length;
				if(response.success == 1){
					var html = "";
					
					html += "<td>"+response["id"]+"</td>";
					for(i=0;i<columns.length;i++){
						html +='<td class="'+columns[i]+'">'+response[columns[i]]+'</td>';

					}
                    html += '<td><a href="javascript:;" id="'+response["id"]+'" class="ajaxEdit"> <span class="glyphicon glyphicon-pencil"></span></a> <a href="javascript:;" id="'+response["id"]+'" class="'+deletebutton+'"><span class="glyphicon glyphicon-trash"></span></a></td>';
                    console.log(html);
					// Append new row as a second last row of a table
					$("."+table+" tr").last().before('<tr id="'+response.id+'">'+html+'</tr>');
					
					if(effect == "slide"){
						// Little hack to animate TR element smoothly, wrap it in div and replace then again replace with td and tr's ;)
						$("."+table+" tr:nth-child("+seclastRow+")").find('td')
						 .wrapInner('<div style="display: none;" />')
						 .parent()
						 .find('td > div')
						 .slideDown(700, function(){
							  var $set = $(this);
							  $set.replaceWith($set.contents());
						});
					}
					else if(effect == "flash"){
					   $("."+table+" tr:nth-child("+seclastRow+")").effect("highlight",{color: '#acfdaa'},100);
					}else
					   $("."+table+" tr:nth-child("+seclastRow+")").effect("highlight",{color: '#acfdaa'},1000);

					// Blank input fields
					$(document).find("."+table).find(inputs).filter(function() {
						// check if input element is blank ??
						this.value = "";
						$(this).removeClass("success").removeClass("error");
					});
				}
			break;
			case "del":
				var seclastRow = $("."+table+" tr").length;
				if(response.success == 1){
					$("."+table+" tr[id='"+response.id+"']").effect("highlight",{color: '#f4667b'},500,function(){
						$("."+table+" tr[id='"+response.id+"']").remove();
					});
				}
			break;
			case "update":
				$("."+cancelbutton).trigger("click");
				for(i=0;i<columns.length;i++){
					$("tr[id='"+response.id+"'] td[class='"+columns[i]+"']").html(response[columns[i]]);
				}
			break;
			case "updatetd":
				//$("."+cancelbutton).trigger("click");
				var newval = $("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").find(inputs).val();
				
				//alert($("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").html());
				$("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").html(newval);

				//$("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").html(newval);
				// remove editing flag
				tdediting = 0;
				$("."+table+" tr[id='"+editingtrid+"'] td[class='"+editingtdcol+"']").effect("highlight",{color: '#acfdaa'},1000);
			break;
		  }
		},
		error: function(){
			alert("Unexpected error, Please try again");
		}
	});
}