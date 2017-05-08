<script src="http://d3js.org/d3.v3.min.js"></script>
<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
 <div id="sidebar" style="display: none;">
    <div class="ui-widget">
       <input id="search" placeholder="Nociones, autores, hitos...">
        <button type="button" onclick="searchNode()">Buscar</button>
    </div>
        <div class="item-group">
            <label class="item-label">Tipos de nodo</label>
            <div id="filterContainer" class="filterContainer checkbox-interaction-group"></div>
        </div>
        <div class="item-group">
            <label class="item-label">Libros</label>
            <div id="filterContainerBooks" class="filterContainerBooks checkbox-interaction-group"></div>
        </div>
</div>
<div id="graph"></div>
<div class="test"></a>

<?php

$nodes_array = array();
$relaciones_array = array();
$ni = 0;
foreach ($view->result as $r => &$result):
//dsm($result);
	
        //$nodepath = drupal_lookup_path('alias',"node/".$result->nid);
		$nodeurl = "node/" . $result->nid;
        $nodepath = drupal_get_path_alias($nodeurl);
        
        if (isset($result->field_field_importancia[0])) {
        	$nodeweight = $result->field_field_importancia[0]['raw']['value'];
        
	       if (!isset($nodeweight)) {
	       	$nodeweight = 3;
	       }	
        } else {
        	$result->field_field_importancia[0]['raw']['value'] = 3;
        	$nodeweight= $result->field_field_importancia[0]['raw']['value'];
        }
        
        $tipo = $result->field_field_tipo[0]['rendered']['#title'];
       //get node url		
        $title = $result->node_title;

        //array_push($nodes_array, $title);
        array_push($nodes_array, array(
    		'name' => $title,
    		'nid' => $result->nid,
    		'weigth' => intval($nodeweight),
    		'url' => $nodepath,
    		'type' => $tipo,
    		
		));

	$relaciones = $result->field_field_relacion;

	if ($relaciones) {
	//print 'targets: <ul>';
		for ($i = 0; $i < count($relaciones); $i++) { 

			$relacion=	$relaciones[$i]['rendered']['relation']['link']['#path'];
			$relacion = str_replace("node/","", $relacion);

			array_push($relaciones_array, array(
    		'source' => $ni,
    		'target' => intval($relacion),
            'type' => $tipo,

		));

			//print '<li>' . $relacion . '</li>';	
		}
	//print '</ul>';

	}
	$ni++;
	?></p>

    <?php endforeach; 


?>

<script>  

	var w = 900,
    	h = 600;
	
	var circleWidth = 6;
	
	var fontFamily = 'Bree Serif',
	    fontSizeHighlight = '1.5em',
	    fontSizeNormal = '1em';

	var palette = {
	      "lightgray": "#819090",
	      "gray": "#708284",
	      "mediumgray": "#536870",
	      "darkgray": "#475B62",
	      "darkblue": "#0A2933",
	      "darkerblue": "#042029",
	      "paleryellow": "#ffcc00",
	      "paleyellow": "#EAE3CB",
	      "yellow": "#ffcc00",
	      "orange": "#BD3613",
	      "red": "#D11C24",
	      "pink": "#C61C6F",
	      "purple": "#595AB7",
	      "blue": "#2176C7",
	      "green": "#259286",
	      "yellowgreen": "#738A05",
	      "hito" : "#ff5050",
	  }


var text_center = false;

var default_node_color = "#ccc";
//var default_node_color = "rgb(3,190,100)";
//function find attribute that we're using a few blocks down here to get ID from drupal NID
	function findWithAttr(array, attr, value) {
	    for(var i = 0; i < array.length; i += 1) {
	        if(array[i][attr] == value) {
	            return i;
	        }
	    }
	    return -1;
	} 
	
var nodesraw = <?php echo json_encode($nodes_array); ?>;
var nodes =JSON.parse(JSON.stringify(nodesraw));

	for (i=0; i< nodes.length; i++) {	
		//console.log(nodes[i]);
		if (nodes[i].weigth < 3) {
			nodes[i].weigth = nodes[i].weigth + 3 ; 
		} else if (nodes[i].weigth == 3) {
			nodes[i].weigth = 6; 
		}
		 else if (nodes[i].weigth > 3) {
			nodes[i].weigth = nodes[i].weigth + 4 ; 

		}
		
	}

    var relations = <?php print drupal_json_encode($relaciones_array); ?>;
	var relations = JSON.parse(JSON.stringify(relations));
 
	var links = [];
	var len = relations.length;
	var targ;
	var targetid;
	var source;
    var type;

	
    for (var i = 0; i < len; i++) { 
        source = relations[i]["source"];
        targetid = relations[i]["target"];
        type = relations[i]["type"];
console.log(relations[i]["type"]);

        targ = findWithAttr(nodes, 'nid', targetid); //here we use our find attr function

        //type = findWithAttr(nodes, 'type', );
        links.push({
            source: nodes[source],
            target: nodes[targ],
            type: type,
            
        });
        }

//Constants for the SVG
var width = 900,
    height = 600;

//Set up the colour scale
var color = d3.scale.category20();

//Set up the force layout
var force = d3.layout.force()
    .charge(-360)
    .linkDistance(100)	    
    .size([width, height]);

//Append a SVG to the body of the html page. Assign this SVG as an object to svg
var svg = d3.select("#graph").append("svg:svg")
    .attr("width", width)
    .attr("height", height);
    //.attr("pointer-events", "all")
    //.append('svg:g')
    //.call(d3.behavior.zoom().on("zoom", redraw))
//    .append('svg:g');

//Read the data from the mis element 
//var mis = document.getElementById('mis').innerHTML;
//graph = JSON.parse(mis);

//Creates the graph data structure out of the json data
force.nodes(nodes)
    .links(links)
    .start();

//Create all the line svgs but without locations yet
var link = svg.selectAll(".link")
    .data(links)
    .enter().append("line")
    .attr("class", "link")
   	.attr("stroke", "#CCC");

//Do the same with the circles for the nodes - no 
var node = svg.selectAll(".node")
    .data(nodes)
    .enter().append("g")
    .attr("class", "node")
    .append('a')
	 .attr("href", function(d){return d.url;})
	//mouseover
	 .on("mouseover", function(d) {
	         //CIRCLE
	          d3.select(this).selectAll("circle")
	          .transition()
	          .duration(250)
	          .style("cursor", "pointer")     
	          .attr("r", function(d){ var m0 = d.weigth + 3; return m0; })
	          .attr("fill", palette.purple);

	          //TEXT
	          d3.select(this).select("text")
	          .transition()
	          .style("cursor", "none")     
	          .duration(250)
	          .style("cursor", "pointer")     
	          .attr("font-size","20px")     
	      })
	 //mouseout
    .on("mouseout", function(d,i) {
	        if (i>0) {
	          //CIRCLE
	          d3.select(this).selectAll("circle")
	          .transition()
	          .duration(250)
	          .attr("r", function(d) { return d.weigth; })
	          .attr("fill",palette.pink);

	          //TEXT
	          var text = d3.select(this).select("text")
	          .transition()
	          .duration(250)
	          .attr("font-size","13px")
	        }
	      })
	.call(force.drag);


  

var circle =  node.append("circle")
    .attr("r", function(d) {  return d.weigth;})                               
	.style("fill", function(d) { 
	if (d.type == "Cita") return palette.yellow;
	else if (d.type == "Hito") return palette.hito;
	else return palette.pink })

node.append("text")
      .attr("dx", 10)
      .attr("dy", ".35em")
      .text(function(d) { return d.name });


//Now we are giving the SVGs co-ordinates - the force layout is generating the co-ordinates which this code is using to update the attributes of the SVG elements
force.on("tick", function () {
    link.attr("x1", function (d) {
        return d.source.x;
    })
        .attr("y1", function (d) {
        return d.source.y;
    })
        .attr("x2", function (d) {
        return d.target.x;
    })
        .attr("y2", function (d) {
        return d.target.y;
    });
    d3.selectAll("circle").attr("cx", function (d) {
        return d.x;
    })
        .attr("cy", function (d) {
        return d.y;
    });
    d3.selectAll("text").attr("x", function (d) {
        return d.x;
    })
        .attr("y", function (d) {
        return d.y;
    });
});

  
	

//search
var optArray = [];
window.onload = function() { 
	for (var i = 0; i < nodes.length; i++) {
    	optArray.push(nodes[i].name);

	}
	optArray = optArray.sort();
	jQuery(function () {
    	jQuery("#search").autocomplete({
	        source: optArray
    	});
	});

}

		
function searchNode() {
    //find the node
    var selectedVal = document.getElementById('search').value;
    var node = svg.selectAll(".node");
    if (selectedVal == "none") {
        node.style("stroke", "white").style("stroke-width", "1");
    } else {
        var selected = node.filter(function (d, i) {
            return d.name != selectedVal;
        });
        selected.style("opacity", "0");
        var link = svg.selectAll(".link")
        link.style("opacity", "0");
        d3.selectAll(".node, .link").transition()
            .duration(7000)
            .style("opacity", 1);
    }
}




///
    // Method to create the filter
    createFilter();
    var lVisibility;
    // Method to create the filter, generate checkbox options on fly
    function createFilter() {
        d3.select(".filterContainer").selectAll("div")
          .data(["Noción", "Hito", "Cita"])
          .enter()  
          .append("div")
          .attr("class", "checkbox-container")
          .append("label")
          .each(function (d) {
                // create checkbox for each data
                d3.select(this).append("input")
                  .attr("type", "checkbox")
                  .attr("id", function (d) {
                      return "chk_" + d;
                   })
                  .attr("checked", true)
                  .on("click", function (d, i) {
                      // register on click event
                    lVisibility = this.checked ? "visible" : "hidden";
                      console.log("cat: "+d+", visibility: "+lVisibility);
                      filterGraph(d, lVisibility);
                   });
                d3.select(this).append("span")
                    .text(function (d) {
                        return d;
                    });
        });
        jQuery("#sidebar").show();
    }

    // Method to filter graph
    function filterGraph(aType, aVisibility) {
       

        circle.style("visibility", function (d, i) {
            var lHideNode = true;
            var nOriginalVisibility = jQuery(this).css("visibility");

                if (d.type == aType) {
                    if (aVisibility == "visible") {
                        return "visible";
                    } else {
                        return "hidden";
                    }

                
            } else {
                return nOriginalVisibility
            }
             
        }); 

         // change the visibility of the connection path
        link.style("visibility", function (o, i) {
            var nOriginalVisibility = jQuery(this).css("visibility");
            if (o.type == aType ) {
                
                if (aVisibility == "visible") {
                    return "visible";        
                } else {
                    return "hidden";
                } 
            } 
            else {
//
                node.each(function (d, i) {
                if (o.target === d.nid ) {
                   return "hidden";
                }
            });
//


                return nOriginalVisibility;
            }

        });
        //d3.select(d3.selectAll("text")[0][i]).style("visibility", "visible");
        
        // change the visibility of the node
        // if all the links with that node are invisibile, the node should also be invisible
        // otherwise if any link related to that node is visibile, the node should be visible
        /*
        circle.style("visibility", function (o, i) {
            var lHideNode = true;
            link.each(function (d, i) {
                if (d.source === o || d.target === o) {
                    if (jQuery(this).css("visibility") === "visible") {
                        lHideNode = false;
                        // we need show the text for this circle
                        d3.select(d3.selectAll("text")[0][i]).style("visibility", "visible");
                        return "visible";
                    }
                }
            });*/
           
    }
</script>



    <?php ?>