<script src="http://d3js.org/d3.v3.min.js"></script>

<div id="graph"></div>

<?php

$nodes_array = array();
$relaciones_array = array();
$ni = 0;
foreach ($view->result as $r => &$result):
//dsm($result);
	
        //$nodepath = drupal_lookup_path('alias',"node/".$result->nid);
		$nodeurl = "node/" . $result->nid;
        $nodepath = drupal_get_path_alias($nodeurl);

        $nodeweight = $result->field_field_node_weight[0]['raw']['value'];
        if (!$nodeweight) {
        	$nodeweight = 3;
        }
       //get node url
		
        $title = $result->node_title;
        //array_push($nodes_array, $title);
        array_push($nodes_array, array(
    		'name' => $title,
    		'nid' => $result->nid,
    		'weight' => strval($nodeweight),
    		'url' => $nodepath,
    		
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
	var circleWidthXXS = 2;
	var circleWidthXS = 3;
	var circleWidthS = 4;
	var circleWidth = 6;
	var circleWidthL = 9;
	var circleWidthXL = 11;

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
	      "paleryellow": "#FCF4DC",
	      "paleyellow": "#EAE3CB",
	      "yellow": "#A57706",
	      "orange": "#BD3613",
	      "red": "#D11C24",
	      "pink": "#C61C6F",
	      "purple": "#595AB7",
	      "blue": "#2176C7",
	      "green": "#259286",
	      "yellowgreen": "#738A05"
	  }

	function findWithAttr(array, attr, value) {
	    for(var i = 0; i < array.length; i += 1) {
	        if(array[i][attr] == value) {
	            return i;
	        }
	    }
	    return -1;
	} 

//var nodes = [{"name":"Prosumidor","nid":"31","weight":"3","url":"nodo\/prosumidor"},{"name":"Produccion par a par","nid":"30","weight":"3","url":"nodo\/produccion-par-par"},{"name":"E-Mail y Software Social","nid":"29","weight":"3","url":"nodo\/e-mail-y-software-social"},{"name":"Educaci\u00f3n Publicitaria","nid":"28","weight":"3","url":"nodo\/educaci\u00f3n-publicitaria"},{"name":"Docentes Web 2.0","nid":"27","weight":"3","url":"nodo\/docentes-web-20"},{"name":"Alfabetizaci\u00f3n Digital","nid":"26","weight":"3","url":"nodo\/alfabetizaci\u00f3n-digital"},{"name":"Televisi\u00f3n","nid":"25","weight":"3","url":"nodo\/televisi\u00f3n"},{"name":"Inmigrante Digital","nid":"24","weight":"3","url":"nodo\/inmigrante-digital"},{"name":"Arado","nid":"23","weight":"3","url":"nodo\/arado"},{"name":"Estribo","nid":"22","weight":"3","url":"nodo\/estribo"},{"name":"Edad Media","nid":"21","weight":"3","url":"nodo\/edad-media"},{"name":"Modernidad ","nid":"19","weight":"4","url":"nodo\/modernidad"},{"name":"Rob\u00f3tica","nid":"18","weight":"5","url":"nodo\/rob\u00f3tica"},{"name":"Comunidad Virtual","nid":"17","weight":"5","url":"nodo\/comunidad-virtual"},{"name":"Cibertexto","nid":"16","weight":"4","url":"nodo\/cibertexto"},{"name":"Lenguaje","nid":"15","weight":3,"url":"nodo\/lenguaje"},{"name":"Knowledge Navigator","nid":"12","weight":3,"url":"hito\/knowledge-navigator"},{"name":"Determinismo tecnol\u00f3gico","nid":"11","weight":3,"url":"node\/11"},{"name":"Hypercard","nid":"10","weight":"2","url":"node\/10"},{"name":"Tecno ciencia","nid":"8","weight":3,"url":"node\/8"},{"name":"Hipertexto","nid":"7","weight":3,"url":"node\/7"},{"name":"Cultura digital","nid":"5","weight":3,"url":"node\/5"},{"name":"La cuarta discontinuidad","nid":"4","weight":3,"url":"node\/4"},{"name":"Nativo digital","nid":"3","weight":3,"url":"node\/3"}];
	
	var nodesraw = <?php echo json_encode($nodes_array); ?>;
	var nodes =JSON.parse(JSON.stringify(nodesraw));


	for (var i = 0; i < nodes.length; i++) {	
		if (nodes[i].weight == 3 ) {
	        	nodes[i].weight = circleWidth;
		}
	}
    
    var relations = <?php print drupal_json_encode($relaciones_array); ?>;
	var relations = JSON.parse(JSON.stringify(relations));
 
	var links = [];
	var len = relations.length;
	var targ;
	var targetid;
	var source;
	
	for (var i = 0; i < len; i++) {	
		source = relations[i]["source"];
		targetid = relations[i]["target"];
		targ = findWithAttr(nodes, 'nid', targetid);
		links.push({
	        source: nodes[source],
	        target: nodes[targ],
	    });
		}

	var vis = d3.select("#graph")
	    .append("svg:svg")
	      .attr("class", "stage")
	      .attr("width", w)
	      .attr("height", h);

	var force = d3.layout.force()
	    .nodes(nodes)
	    .links([])
	    .gravity(0.5)
	    .charge(-2000)
	        .linkDistance(30)
	    .size([w, h]);

	 var link = vis.selectAll(".link")
	        .data(links)
	        .enter().append("line")
	          .attr("class", "link")
	          .attr("stroke", "#CCC")
	          .attr("fill", "none");

	 var node = vis.selectAll("circle.node")
	      .data(nodes)
	      .enter().append("g")
	      .attr("class", "node")
	      .append('a')
	      .attr({})
	      .attr("href", function(d){return d.url;})
	      
	      //MOUSEOVER
	      .on("mouseover", function(d,i) {
	        if (i>0) {
	          //CIRCLE
	          d3.select(this).selectAll("circle")
	          .transition()
	          .duration(250)
	          .style("cursor", "pointer")     
	          .attr("r", function(d){ return circleWidth; })
	          .attr("fill",palette.orange);

	          //TEXT
	          d3.select(this).select("text")
	          .transition()
	          .style("cursor", "none")     
	          .duration(250)
	          .style("cursor", "pointer")     
	          .attr("font-size","1.5em")
	          .attr("x", 15 )
	          .attr("y", 5 )
	        } else {
	          //CIRCLE
	          d3.select(this).selectAll("circle")
	          .style("cursor", "pointer")     

	          //TEXT
	          d3.select(this).select("text")
	          .style("cursor", "pointer")     
	        }
	      })

	      //MOUSEOUT
	      .on("mouseout", function(d,i) {
	        if (i>0) {
	          //CIRCLE
	          d3.select(this).selectAll("circle")
	          .transition()
	          .duration(250)
	          .attr("r", function(d) { return 6; })
	          .attr("fill",palette.pink);

	          //TEXT
	          d3.select(this).select("text")
	          .transition()
	          .duration(250)
	          .attr("font-size","1em")
	          .attr("x", 8 )
	          .attr("y", 4 )
	        }
	      })
	      .call(force.drag);

	    //CIRCLE
	    node.append("svg:circle")
	      .attr("cx", function(d) { return d.x; })
	      .attr("cy", function(d) { return d.y; })
	      //.attr("cx", function(d) { return d.x = Math.max(r, Math.min(w - r, d.x)); })
    	  //.attr("cy", function(d) { return d.y = Math.max(r, Math.min(h - r, d.y)); });
	      .attr("r", function(d) {  return d.weight;})                               
	      .attr("fill", function(d, i) { if (i>0) { return  palette.pink; } else { return palette.pink } } )

	    //TEXT
	    node.append("text")
	      .text(function(d, i) { return d.name; })
	    .attr("x",    function(d, i) { return 6; })
	      .attr("y",            function(d, i) { if (i>0) { return d.weight; }    else { return d.weight } })
	      .attr("font-family",  "Bree Serif")
	      .attr("fill",         function(d, i) {  return  palette.darkgray;  })
	      .attr("font-size",    function(d, i) {  return  "1em"; })
	      .attr("text-anchor",  function(d, i) { if (i>0) { return  "beginning"; }      else { return "end" } })


	force.on("tick", function(e) {
	  node.attr("transform", function(d, i) {     
	        return "translate(" + d.x + "," + d.y + ")"; 
	    });
	    
	   link.attr("x1", function(d)   { return d.source.x; })
	       .attr("y1", function(d)   { return d.source.y; })
	       .attr("x2", function(d)   { return d.target.x; })
	       .attr("y2", function(d)   { return d.target.y; })
	});

	force.start();

	

</script>

    <p>Fin de los nodos. Soy estático!</p>


    <?php ?>