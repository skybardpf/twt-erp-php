/**
 * @author Burtsev R.V. <roman@artektiv.ru>
 */
(function($){
    var Renderer = function(canvas)
    {
        var canvas = $(canvas).get(0);
        var ctx = canvas.getContext("2d");
        var gfx = arbor.Graphics(canvas);
        var particleSystem = null;

        var that = {
            init:function(system){
                // начальная инициализация
                particleSystem = system;
                particleSystem.screenSize(canvas.width, canvas.height);
                particleSystem.screenPadding(40);
                that.initMouseHandling();
            },

            redraw:function()
            {
                if (!particleSystem) return;

                gfx.clear();

                var nodeBoxes = {};

                // рисуем объекты
                particleSystem.eachNode(function (node, pt)
                {
                    var label = node.data.label||"";
                    var w = ctx.measureText(""+label).width + 10;
                    if (!(""+label).match(/^[ \t]*$/)){
                        pt.x = Math.floor(pt.x);
                        pt.y = Math.floor(pt.y);
                    } else {
                        label = null
                    }

                    if (node.data.color) ctx.fillStyle = node.data.color;
                    else ctx.fillStyle = "rgba(0,0,0,.2)";
                    if (node.data.color=='none') ctx.fillStyle = "white";
                    if (node.data.type=='circle'){
                        gfx.oval(pt.x-w/2, pt.y-w/2, w,w, {fill:ctx.fillStyle});
                        nodeBoxes[node.name] = [pt.x-w/2, pt.y-w/2, w, w]
                    } else {
                        gfx.rect(pt.x-w/2, pt.y-10, w,20, 4, {fill:ctx.fillStyle});
                        nodeBoxes[node.name] = [pt.x-w/2, pt.y-11, w, 22]
                    }
                    // названия объектов
                    if (label) {
                        ctx.font = "12px Helvetica";
                        ctx.textAlign = "center";
                        ctx.fillStyle = "white";
                        if (node.data.color=='none') ctx.fillStyle = '#333333';
                        ctx.fillText(label||"", pt.x, pt.y+4);
                        ctx.fillText(label||"", pt.x, pt.y+4);
                    }
                });

                // рисуем объекты
                particleSystem.eachEdge(function(edge, pt1, pt2){
                    // edge: {source:Node, target:Node, length:#, data:{}}
                    // pt1:  {x:#, y:#}  source position in screen coords
                    // pt2:  {x:#, y:#}  target position in screen coords

                    var weight = edge.data.weight;
                    var color = edge.data.color;

                    if (!color || (""+color).match(/^[ \t]*$/)) color = null;

                    // find the start point
                    var tail = intersect_line_box(pt1, pt2, nodeBoxes[edge.source.name]);
                    var head = intersect_line_box(tail, pt2, nodeBoxes[edge.target.name]);

                    ctx.save();
                    ctx.beginPath();
                    ctx.lineWidth = (!isNaN(weight)) ? parseFloat(weight) : 1;
                    ctx.strokeStyle = (color) ? color : "#cccccc";
                    ctx.fillStyle = null;

                    ctx.moveTo(tail.x, tail.y);
                    ctx.lineTo(head.x, head.y);
                    ctx.stroke();
                    ctx.restore();

                    // рисуем стрелки
                    ctx.save();
                    // move to the head position of the edge we just drew
                    var wt = !isNaN(weight) ? parseFloat(weight) : 1;
                    var arrowLength = 12 + wt;
                    var arrowWidth = 4 + wt;
                    ctx.fillStyle = (color) ? color : "#cccccc";
                    ctx.translate(head.x, head.y);
                    ctx.rotate(Math.atan2(head.y - tail.y, head.x - tail.x));

                    // delete some of the edge that's already there (so the point isn't hidden)
                    ctx.clearRect(-arrowLength/2,-wt/2, arrowLength/2,wt);

                    // draw the chevron
                    ctx.beginPath();
                    ctx.moveTo(-arrowLength, arrowWidth);
                    ctx.lineTo(0, 0);
                    ctx.lineTo(-arrowLength, -arrowWidth);
                    ctx.lineTo(-arrowLength * 0.8, -0);
                    ctx.closePath();
                    ctx.fill();
                    ctx.restore();
                    // end

                    ctx.fillStyle = "black";
                    ctx.font = "12px Helvetica";
                    ctx.fillText(edge.data.percent+"%", (pt1.x + pt2.x) / 2, (pt1.y + pt2.y) / 2);
                });
            },

            initMouseHandling:function(){	// события с мышью
                var dragged = null;			// вершина которую перемещают
                var handler = {
                    clicked:function(e){	// нажали
                        var pos = $(canvas).offset();	// получаем позицию canvas
                        _mouseP = arbor.Point(e.pageX-pos.left, e.pageY-pos.top); // и позицию нажатия кнопки относительно canvas
                        dragged = particleSystem.nearest(_mouseP);	// определяем ближайшую вершину к нажатию
                        if (dragged && dragged.node !== null){
                            dragged.node.fixed = true;	// фиксируем её
                        }
                        $(canvas).bind('mousemove', handler.dragged);	// слушаем события перемещения мыши
                        $(window).bind('mouseup', handler.dropped);		// и отпускания кнопки
                        return false;
                    },
                    dragged:function(e){	// перетаскиваем вершину
                        var pos = $(canvas).offset();
                        var s = arbor.Point(e.pageX-pos.left, e.pageY-pos.top);

                        if (dragged && dragged.node !== null){
                            var p = particleSystem.fromScreen(s);
                            dragged.node.p = p;	// тянем вершину за нажатой мышью
                        }

                        return false;
                    },
                    dropped:function(e){ // отпустили
                        if (dragged===null || dragged.node===undefined) return;	// если не перемещали, то уходим
                        if (dragged.node !== null) dragged.node.fixed = false;	// если перемещали - отпускаем
                        dragged = null; // очищаем
                        $(canvas).unbind('mousemove', handler.dragged); // перестаём слушать события
                        $(window).unbind('mouseup', handler.dropped);
                        _mouseP = null;
                        return false;
                    }
                };
                // слушаем события нажатия мыши
                $(canvas).mousedown(handler.clicked);
            }
        };

        // функции для рисования связей
        var intersect_line_line = function(p1, p2, p3, p4)
        {
            var denom = ((p4.y - p3.y)*(p2.x - p1.x) - (p4.x - p3.x)*(p2.y - p1.y));
            if (denom === 0) return false;// lines are parallel
            var ua = ((p4.x - p3.x)*(p1.y - p3.y) - (p4.y - p3.y)*(p1.x - p3.x)) / denom;
            var ub = ((p2.x - p1.x)*(p1.y - p3.y) - (p2.y - p1.y)*(p1.x - p3.x)) / denom;

            if (ua < 0 || ua > 1 || ub < 0 || ub > 1)  return false;
            return arbor.Point(p1.x + ua * (p2.x - p1.x), p1.y + ua * (p2.y - p1.y));
        };

        var intersect_line_box = function(p1, p2, boxTuple)
        {
            var p3 = {x:boxTuple[0], y:boxTuple[1]},
                w = boxTuple[2],
                h = boxTuple[3];

            var tl = {x: p3.x, y: p3.y};
            var tr = {x: p3.x + w, y: p3.y};
            var bl = {x: p3.x, y: p3.y + h};
            var br = {x: p3.x + w, y: p3.y + h};

            return intersect_line_line(p1, p2, tl, tr) ||
                intersect_line_line(p1, p2, tr, br) ||
                intersect_line_line(p1, p2, br, bl) ||
                intersect_line_line(p1, p2, bl, tl) ||
                false
        };

        return that;
    };

    $(document).ready(function(){
        var data = {};
        var i;
        data.nodes = {};
        data.edges = {};

        // nodes
        for (i in raw_data) {
            data.nodes[raw_data[i].id1] = {color:"#c0c0c0", type: raw_data[i].type1, label: raw_data[i].title1};
            data.nodes[raw_data[i].id2] = {color:"#ff3030", type: raw_data[i].type2, label: raw_data[i].title2};
            data.edges[raw_data[i].id2] = {};// создаем элементы владельцев
        }
        // edges
        for (i in raw_data) { // назначаем владельцам элементы, кот. они владеют
            data.edges[raw_data[i].id2][raw_data[i].id1] = {length:.8, percent:raw_data[i].percent, color:"#8f8f8f"};
        }

        var sys = arbor.ParticleSystem();
        sys.parameters({stiffness:900, repulsion:1000, gravity:true, dt:0.015});
        sys.renderer = Renderer("#viewport");
        sys.graft(data)
    });
})(this.jQuery);