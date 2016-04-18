<?php include_once("includes/header.php"); ?>
<?php //restrictedPage(); ?>


<?php 

CONST GOOGLURL = "https://www.googleapis.com/pagespeedonline/v2/runPagespeed/?url=";


 ?>

<style>
em {
	color: #c7254e;
    background-color: #f9f2f4;
}
.ruleHeader{
	cursor:pointer;
}
.resultvalues{
    max-width: 72%;
    word-wrap: break-word;
    }
</style>

<div class="panel panel-default" style="" id="">
<div class="panel-heading">Google Page Speed Insights</div>
	<div class="panel-body">
	  		<div class="ajaxloading" style="position:absolute;left:0px;top:0px;width:100%;height:100%;background-color: rgba(255,255,255,0.7);z-index:9999;border-radius:7px;display:none;">
	  		    <div style="width:200px;height:200px;background-color:#000;border-radius:100%;text-align:center;line-height:200px;margin:0 auto;position:absolute;top:50%;margin-top:-100px;left:50%;margin-left:-100px">
	  		        <img src="images/LOADING.gif" />
	  		    </div>
	  		</div>
  		<p>This tool will give you the page ranking from google insights, however it will include the score factor for each section</p>
  		<p>
  			<em>Links:</em><br />
  			Leverage browser caching: <a href="http://wiki.561media.com/index.php/.htaccess_browser_caching" target="_blank">WIKI Page</a><br />
  			Enable Compression: <a href="http://wiki.561media.com/index.php/.htaccess_Compression" target="_blank">WIKI Page</a><br />
  			Minify CSS: <a href="https://cssminifier.com/" target="_blank">https://cssminifier.com/</a><br />
  			Minify Javascript: <a href="https://javascript-minifier.com/" target="_blank">https://javascript-minifier.com/</a><br />
  			<!-- Leverage browser caching: <a href="" target="_blank"></a><br /> -->
		</p>
 		<input class="search form-control" placeholder="https://www.example.com" /><br />
 		<button id="btnGO" class="btn btn-default">Get Insights</button>
		
		<div id="results" style="margin-top:20px;">
			
		</div>


	</div>

</div>
<script>
	$(document).ready(function(){
		$(".search").focus();
		$(".search").keypress(function(e){
            if( e.which == 13 ){
                $("#btnGO").click();
            };
        });


		$("#btnGO").click(function(){
	      	$('.ajaxloading').fadeIn();

	      	//if there is no http://
	      	if($(".search").val().indexOf("http://") < 0 && $(".search").val().indexOf("https://") < 0){
	      		$(".search").val("http://"+$(".search").val())
	      	}


			$("#results").empty();

	        $.ajax({
	          url: '<?php echo GOOGLURL ?>' + $(".search").val() + "&screenshot=true&strategy=desktop",
	          dataType : 'json',
	          cache: false,
	          success: function(desktop_result){
	          	$.ajax({
	          	  url: '<?php echo GOOGLURL ?>' + $(".search").val() + "&screenshot=true&strategy=mobile",
	          	  dataType : 'json',
	          	  cache: false,
	          	  success: function(mobile_result){

	          	  	//console.log(desktop_result);
	          	  	//console.log(mobile_result);

	          	  	var desktop_score = desktop_result.ruleGroups.SPEED.score;
	          	  	var score_label = getScoreLabel(desktop_score);



	          	  	var desktop_output = '<div>';
	          	  	desktop_output += '     <div class="panel panel-default">\n';
	          	  	desktop_output += '          <div class="panel-heading"><span class="label ' + score_label + '">' + desktop_score + '</span> Desktop</div>\n';
	          	  	desktop_output += '          <div class="panel-body">';
	          	  	//loop through each of the results and only show the ones that have a high impact
	 	          	  	desktop_output += '          <div class="image" style="float:left;padding-right:50px;">';
	          	  	desktop_output += '<img src="data:image/png;base64,'+desktop_result.screenshot.data.replace(/_/g,'/').replace(/-/g,'+')+'" />';
	          	  	desktop_output += '          </div><div style="float:left;" class="resultvalues">';
         	  	
		          	desktop_result.formattedResults.ruleResults = impactOrder(desktop_result.formattedResults.ruleResults, "ruleImpact");

	          	  	$.each(desktop_result.formattedResults.ruleResults, function(key, value){
	          	  		if(value.ruleImpact > 0){
		          	  		desktop_output += '<div class="rule"><span class="label '+ getImpactLabel(value.ruleImpact)+'">'+value.ruleImpact.toFixed(0)+'</span> - <span class="ruleHeader">'+value.localizedRuleName+'</span><ul style="display:none;">\n';
		          	  		if(value.urlBlocks){
		          	  			$.each(value.urlBlocks, function (blockKey,blockValue){
		          	  				$.each(blockValue.urls, function(a,url){
		          	  					$.each(url.result.args, function(c,args){
		          	  						if(url.result.format !== undefined){
		          	  							url.result.format = url.result.format.replace("{{"+args.key+"}}", args.value);
		          	  						}
		          	  					});
		          	  					desktop_output += '<li>' + url.result.format + '</li>';
		          	  				});
		          	  			});
		          	  		}
		          	  		desktop_output += "</ul></div>";
		          	  	}
	          	  	});
	          	  	desktop_output += '         </div> </div>\n';
	          	  	desktop_output += "     </div>\n";
	          	  	desktop_output += "</div>\n";




	          	  	var mobile_score = mobile_result.ruleGroups.SPEED.score;
	          	  	var m_score_label = getScoreLabel(mobile_score);


	          	  	var mobile_output = '<div>';
	          	  	mobile_output += '     <div class="panel panel-default">\n';
	          	  	mobile_output += '          <div class="panel-heading"><span class="label ' + m_score_label + '">' + mobile_score + '</span> mobile</div>\n';
	          	  	mobile_output += '          <div class="panel-body">';
	          	  	mobile_output += '          <div class="image" style="float:left;padding-right:50px;">';
	          	  	mobile_output += '<img src="data:image/png;base64,'+mobile_result.screenshot.data.replace(/_/g,'/').replace(/-/g,'+')+'" />';
	          	  	mobile_output += '          </div><div style="float:left;" class="resultvalues">';
	          	  	//loop through each of the results and only show the ones that have a high impact
	          	  	
		          	mobile_result.formattedResults.ruleResults = impactOrder(mobile_result.formattedResults.ruleResults, "ruleImpact");

	          	  	$.each(mobile_result.formattedResults.ruleResults, function(key, value){
	          	  		if(value.ruleImpact > 0){
		          	  		mobile_output += '<div class="rule"><span class="label '+ getImpactLabel(value.ruleImpact)+'">'+value.ruleImpact.toFixed(0)+'</span> - <span class="ruleHeader">'+value.localizedRuleName+'</span><ul style="display:none;">\n';
		          	  		if(value.urlBlocks){
		          	  			$.each(value.urlBlocks, function (blockKey,blockValue){
		          	  				$.each(blockValue.urls, function(a,url){
		          	  					$.each(url.result.args, function(c,args){
		          	  						if(url.result.format !== undefined){
		          	  							url.result.format = url.result.format.replace("{{"+args.key+"}}", args.value);
		          	  						}
		          	  					});
		          	  					mobile_output += '<li>' + url.result.format + '</li>';
		          	  				});
		          	  			});
		          	  		}
		          	  		mobile_output += "</ul></div>";
		          	  	}
	          	  	});
	          	  	mobile_output += '          </div></div>\n';
	          	  	mobile_output += "     </div>\n";
	          	  	mobile_output += "</div>\n";





	          	  	$("#results").append(desktop_output);
	          	  	$("#results").append(mobile_output);
	      			$('.ajaxloading').fadeOut();


	          	  }
	          	}).fail(function(mobile_error){
				   $("#results").append(mobile_error);
				   $('.ajaxloading').fadeOut();
	          	});
	          }
	        }).fail(function(desktop_error) {
			   $("#results").append(desktop_error);
			   $('.ajaxloading').fadeOut();
			});
		});
		$("body").delegate(".ruleHeader", "click", function(){
			//alert("click");
			$(this).next("ul").toggle();
		});



	});



	function getScoreLabel(score){

		if(score < 40){
			return "label-danger";
		}else if(score >= 40 && score < 60 ){
			return "label-warning";
		}else if(score >= 60 && score < 80){
			return "label-info";
		}else{
			return "label-success";
		}
	}
	function getImpactLabel(score){
		if(score >= 40){
			return "label-danger";
		}else if(score < 40 && score >= 20 ){
			return "label-warning";
		}else if(score < 20 && score >= 10){
			return "label-info";
		}else{
			return "label-success";
		}
	}
	function impactOrder(data, attr) {
    var arr = [];
    for (var prop in data) {
        if (data.hasOwnProperty(prop)) {
            var obj = {};
            obj[prop] = data[prop];
            obj.tempSortName = data[prop][attr];
            arr.push(obj);
        }
    }

    arr.sort(function(a, b) {
        var at = a.tempSortName,
            bt = b.tempSortName;
        return at < bt ? 1 : ( at > bt ? -1 : 0 );
    });

    var result = [];
    for (var i=0, l=arr.length; i<l; i++) {
        var obj = arr[i];
        delete obj.tempSortName;
        for (var prop in obj) {
            if (obj.hasOwnProperty(prop)) {
                var id = prop;
            }
        }
        var item = obj[id];
        result.push(item);
    }
    return result;
}
</script>

<?php include_once("includes/footer.php"); ?>
<!-- screenshot.data.replace(/_/g,'/').replace(/-/g,'+') -->

