
const UML_CLASS_COLOR_BACKGROUND = "#eaffb0";
const UML_RELATION_COLOR = "black";
const UML_PROPERTY_NAME_COLOR = "#0055dd";
const UML_PROPERTY_VALUE_COLOR = "white";
const UML_METHOD_NAME_COLOR = "#0055dd";
const UML_DATA_TYPE_COLOR = "#000088";
const UML_VISIBILITY_CHAR_COLOR = "#ff0000";

const CONTEXT_MENU_BACKGROUND_COLOR = "lightblue";
const SELECTION_COLOR = "deepskyblue";
const UML_RELATION_TYPES = [
    "asociación",
    "herencia",
    "realización",
    "dependencia",
    "agregación",
    "composición"
];

function init() {
    const $ = go.GraphObject.make; // for conciseness in defining templates
    myDiagram =
        $(go.Diagram, "myDiagramDiv", // must name or refer to the DIV HTML element
            {
                "grid.visible": false,
                "draggingTool.dragsLink": true,
                "draggingTool.isGridSnapEnabled": true,
                "linkingTool.isUnconnectedLinkValid": true,
                "linkingTool.portGravity": 20,
                "relinkingTool.isUnconnectedLinkValid": true,
                "relinkingTool.portGravity": 20,
                "relinkingTool.fromHandleArchetype": $(go.Shape, "Diamond", {
                    segmentIndex: 0,
                    cursor: "pointer",
                    desiredSize: new go.Size(15, 15),
                    fill: "tomato",
                    stroke: "darkred"
                }),
                "relinkingTool.toHandleArchetype": $(go.Shape, "Diamond", {
                    segmentIndex: -1,
                    cursor: "pointer",
                    desiredSize: new go.Size(15, 15),
                    fill: "darkred",
                    stroke: "tomato"
                }),
                "linkReshapingTool.handleArchetype": $(go.Shape, "Diamond", {
                    desiredSize: new go.Size(10, 10),
                    fill: "lightblue",
                    stroke: SELECTION_COLOR
                }),
                "rotatingTool.handleAngle": 270,
                "rotatingTool.handleDistance": 30,
                "rotatingTool.snapAngleMultiple": 15,
                "rotatingTool.snapAngleEpsilon": 15,
                "undoManager.isEnabled": true,
                
                //contextMenu: contextMenuOfDiagram
                "allowCopy": false
            }
        );

    /*myDiagram.commandHandler.doKeyDown = function () { // must be a function, not an arrow =>
        var e = this.diagram.lastInput;
        if ((e.key === "c") or(e.key === "C") or(e.key === "v") or(e.key === "V")) {
            // could also check for e.control or e.shift 
        } 
    };*/
    
    myDiagram.commandHandler.doKeyDown = function() { // must be a function, not an arrow =>
      var e = this.diagram.lastInput;
      // The meta (Command) key substitutes for "control" for Mac commands
      var control = e.control || e.meta;
      var key = e.key;
      // Quit on any undo/redo key combination:
      if (control && (key === 'c' || key === 'v')) return;
    
      // call base method with no arguments (default functionality)
      go.CommandHandler.prototype.doKeyDown.call(this);
    };
    
    myDiagram.addDiagramListener("LinkDrawn", e => {
        if (e.subject.part instanceof go.Link){
            myDiagram.model.commit(m => { 
                m.set(e.subject.part.data, "relationType", 'asociación');
            });
        }
    });


    myDiagram.addDiagramListener("ObjectDoubleClicked", e => {
        if (e.subject.part instanceof go.Link){
            // Cambia los valores del tipo de realcion UML.
            const data = e.subject.part.data;
            let newIndex = 0;
            if (data.relationType != null){
                const actualIndex = UML_RELATION_TYPES.indexOf(data.relationType);
                if (actualIndex >= 0){
                    newIndex = (actualIndex == UML_RELATION_TYPES.length - 1) ? 0 : actualIndex + 1;
                }
            }
            myDiagram.model.commit(m => { 
                m.set(data, "relationType", UML_RELATION_TYPES[newIndex]);
            });
        }else{
            const key = e.subject.part.data.key;
            const node = myDiagram.findNodeForKey(key);
            //Listo para futuras modificaciones...
        }
    });

    
    /**
     * Define a function for creating a "port" that is normally transparent.
     * @param name - Is used as the GraphObject.portId.
     * @param spot - Is used to control how links connect and where the port is positioned on the node.
     * @param output - Control whether the user can draw links from the port.
     * @param input - Control whether the user can draw links to the port.
     */
    function makePort(name, spot, output, input) {
        // the port is basically just a small transparent circle
        return $(go.Shape, "Circle", {
            fill: null, // not seen, by default; set to a translucent gray by showSmallPorts, defined below
            stroke: null,
            desiredSize: new go.Size(15, 15),
            alignment: spot, // align the port on the main Shape
            alignmentFocus: spot, // just inside the Shape
            portId: name, // declare this object to be a "port"
            fromSpot: spot,
            toSpot: spot, // declare where links may connect at this port
            fromLinkable: output,
            toLinkable: input, // declare whether the user may draw links to/from here
            cursor: "pointer" // show a different cursor to indicate potential link point
        });
    }

    /**
     * Muestra u oculta los puertos de conexion de color rojo traslucido.
     * @param node - Objeto nodo al cual se le deben dibujar los puertos.
     * @param show - Booleano que se pone a true para que se muestren.
     */
    function showSmallPorts(node, show) {
        node.ports.each(port => {
            if (port.portId !== "") { // don't change the default port, which is the big shape
                port.fill = show ? "rgba(250,0,0,.7)" : null;
            }
        });
    }


    var nodeSelectionAdornmentTemplate =
        $(go.Adornment, "Auto",
            $(go.Shape, "RoundedRectangle", {
                fill: null,
                stroke: SELECTION_COLOR,
                strokeWidth: 3
            }),
            $(go.Placeholder)
        );

    

    /**
     * Devuelve un objet boton para los menus contextuales.
     * @param text - Texto que debe mostrar el boton.
     * @param action - Funcion responsable de manejar el evento.
     */
    function makeButton(text, action) {
        return $("ContextMenuButton", 
            $(go.TextBlock, text, {
                margin: 5,
                alignment: go.Spot.Left,
                font: "bold 12pt sans-serif",
                editable: false
            }), 
            { click: action }
        );
    }


    function addPropertyUML(nodeObject){
        myDiagram.model.commit(m => { 
            m.addArrayItem(
                nodeObject.part.data.properties,
                {
                    name:"propiedad", 
                    type:"tipo",
                    visibility:"public"
                }
            );
        });                 
    }


    function addMethodUML(nodeObject){
        myDiagram.model.commit(m => { 
            m.addArrayItem(
                nodeObject.part.data.methods,
                {
                    name:"método", 
                    type:"tipo",
                    visibility:"public"
                }
            );
        });                 
    }
    

    function addParameterizedMethodUML(nodeObject){
        myDiagram.model.commit(m => { 
            m.addArrayItem(
                nodeObject.part.data.methods,
                {
                    name:"método", 
                    type:"tipo",
                    visibility:"public",
                    param1Name: "parametro", 
                    param1Type: "tipo"
                }
            );
        });                 
    }


    /**
     * Establece la visibilidad de una propiedad o metodo de una clase UML
     * @param Obj - Objeto al que se va a editar la visibilidad.
     * @param visibility - String con el nombre del tipo visibilidad UML.
     */
    function setVisibility(Obj, visibility){
        myDiagram.model.commit(m => {                       // m == the Model
            m.set(Obj.part.data, "visibility", visibility);
        });
    }


    /**
     * Elimina una propiedad o metodo de una clase UML
     * @param Obj - Objeto al que se va a eliminar.
     */
     function deleteObject(obj){
        myDiagram.model.commit(m => {  
            m.nodeDataArray.forEach(element => {
                let index = element.properties.indexOf(obj.part.data);
                if (index >= 0) {
                    m.removeArrayItem(element.properties, index);
                }else{
                    index = element.methods.indexOf(obj.part.data)
                    if (index >= 0) {
                        m.removeArrayItem(element.methods, index);    
                    }
                }
            });
        });
    }


    /**
     * Establece el tipo de dato de una propiedad o metodo de una clase UML
     * @param Obj - Objeto al que se va a editar el tipo de dato.
     * @param dataType - String con el nombre del tipo de dato de la propiedad UML.
     */
    function setDataType(Obj, dataType, varType){
        myDiagram.model.commit(m => {                       // m == the Model
            m.set(Obj.part.data, varType, dataType);
        });
    }

    // Este es el menu contextual de las clases UML del diagrama.
    var contextMenuOfClass =
        $("ContextMenu",
            makeButton("Agregar propiedad", (e, obj) => addPropertyUML(obj)),
            makeButton("Agregar método", (e, obj) => addMethodUML(obj)),
            makeButton("Método con parámetro", (e, obj) => addParameterizedMethodUML(obj)),
            makeButton("Eliminar clase", (e, obj) => e.diagram.commandHandler.deleteSelection())
        );

    // Este es el menu contextual de los enlaces UML del diagrama.
    var contextMenuOfLink =
        $("ContextMenu",
            makeButton(UML_RELATION_TYPES[0], (e, linkObject) => setLinkType(linkObject, 0)),
            makeButton(UML_RELATION_TYPES[1], (e, linkObject) => setLinkType(linkObject, 1)),
            makeButton(UML_RELATION_TYPES[2], (e, linkObject) => setLinkType(linkObject, 2)),
            makeButton(UML_RELATION_TYPES[3], (e, linkObject) => setLinkType(linkObject, 3)),
            makeButton(UML_RELATION_TYPES[4], (e, linkObject) => setLinkType(linkObject, 4)),
            makeButton(UML_RELATION_TYPES[5], (e, linkObject) => setLinkType(linkObject, 5))
        );

    
    // Este es el menu contextual de las propiedades de las clases UML del diagrama.
    var contextMenuOfProperty =
        $("ContextMenu",
            makeButton("Establecer Público", (e, obj) => setVisibility(obj, "public")),
            makeButton("Establecer Privado", (e, obj) => setVisibility(obj, "private")),
            makeButton("Establecer Protegido", (e, obj) => setVisibility(obj, "protected")),
            makeButton("Establecer Paquete", (e, obj) => setVisibility(obj, "package")),
            makeButton("Eliminar", (e, obj) => deleteObject(obj))
        );
        
    
    // Este es el menu contextual del tipo de datos de las propiedades y parametros en clases UML.
    function contextMenuOfTypes(varType){
        return $("ContextMenu",
            makeButton("Entero", (e, obj) => setDataType(obj, "Integer", varType)),
            makeButton("Fraccionario", (e, obj) => setDataType(obj, "Float", varType)),
            makeButton("Booleano", (e, obj) => setDataType(obj, "Boolean", varType)),
            makeButton("Cadena", (e, obj) => setDataType(obj, "String", varType)),
            makeButton("Fecha", (e, obj) => setDataType(obj, "Date", varType)),
            makeButton("Tiempo", (e, obj) => setDataType(obj, "Time", varType)),
            makeButton("FechaTiempo", (e, obj) => setDataType(obj, "DateTime", varType)),
            makeButton("Lista de Enteros", (e, obj) => setDataType(obj, "List<Integer>", varType)),
            makeButton("Lista de Fraccionarios", (e, obj) => setDataType(obj, "List<Float>", varType)),
            makeButton("Lista de Booleanos", (e, obj) => setDataType(obj, "List<Boolean>", varType)),
            makeButton("Lista de Cadenas", (e, obj) => setDataType(obj, "List<String>", varType)),
            makeButton("Lista de Fechas", (e, obj) => setDataType(obj, "List<Date>", varType)),
            makeButton("Lista de Tiempos", (e, obj) => setDataType(obj, "List<Time>", varType)),
            makeButton("Lista de FechaTiempo", (e, obj) => setDataType(obj, "List<DateTime>", varType))
        );
    }
        
    
    /**
     * Edita un objeto link estableciendo el tipo de enlace UML.
     * @param linkObject - Objeto link que se va a editar.
     * @param linkTypeToSet - String con el nombre del tipo de enlace UML.
     */
    function setLinkType(linkObject, indexOfLinkTypeToSet){
        const data = linkObject.part.adornedPart.data; 
        myDiagram.model.commit(m => {                       // m == the Model
            m.set(data, "relationType", UML_RELATION_TYPES[indexOfLinkTypeToSet]);
        });

    }


    myDiagram.contextMenu =
        $("ContextMenu",
            makeButton("Nueva clase", 
                (e, obj) => addNewClass(
                    e.diagram.toolManager.contextMenuTool.mouseDownPoint.x, 
                    e.diagram.toolManager.contextMenuTool.mouseDownPoint.y
                )
            ),
            makeButton("Nueva relación", 
                (e, obj) => addNewLink(
                    e.diagram.toolManager.contextMenuTool.mouseDownPoint.x, 
                    e.diagram.toolManager.contextMenuTool.mouseDownPoint.y
                )
            )
        );

    

    /**
     * Resalta un objetocambiando su color.
     * @param element - Objeto que se debe resaltar o no.
     * @param show - Booleano que se pone a true para resaltar el objeto.
     */
     function highlight(element, show) {
        myDiagram.model.commit(m => { 
            element.background = show ? "rgba(0,0,250,0.2)" : null;
        });
    }


    // show visibility or access as a single character at the beginning of each property or method
    function convertVisibility(v) {
        switch (v) {
            case "public":
                return "+";
            case "private":
                return "-";
            case "protected":
                return "#";
            case "package":
                return "~";
            default:
                return v;
        }
    }

    
    function textBlockCommon(bold, color, editable){
        return {
            isMultiline: false,
            textAlign: "center",
            font: (bold ? "bold" : "") + " 10pt helvetica, arial, sans-serif", 
            margin: 2,
            minSize: new go.Size(10, NaN),
            stroke: color,
            editable: editable
        };
    }




    // the item template for properties
    var propertyTemplate =
        $(go.Panel, "Horizontal",
            // property visibility/access
            $(go.TextBlock, textBlockCommon(false, UML_VISIBILITY_CHAR_COLOR, false), {
                    width: 12,
                    mouseEnter: (e, obj) => highlight(obj, true),
                    mouseLeave: (e, obj) => highlight(obj, false)    
                },
                new go.Binding("text", "visibility", convertVisibility)),
            // property name, underlined if scope=="class" to indicate static property
            $(go.TextBlock, textBlockCommon(true, UML_PROPERTY_NAME_COLOR, true), {
                mouseEnter: (e, obj) => highlight(obj, true),
                mouseLeave: (e, obj) => highlight(obj, false)    
            },
                new go.Binding("text", "name").makeTwoWay(),
                new go.Binding("isUnderline", "scope", s => s[0] === 'c')),
            // property type, if known
            $(go.TextBlock, textBlockCommon(false, UML_PROPERTY_NAME_COLOR, false),
                new go.Binding("text", "type", t => t ? ": " : "")),
            $(go.TextBlock, textBlockCommon(false, UML_DATA_TYPE_COLOR, false), {
                    contextMenu: contextMenuOfTypes("type"),
                    mouseEnter: (e, obj) => highlight(obj, true),
                    mouseLeave: (e, obj) => highlight(obj, false)    
                },
                new go.Binding("text", "type").makeTwoWay()),
            // property default value, if any
            //$(go.TextBlock, textBlockCommon(false, UML_PROPERTY_VALUE_COLOR, true), {
            //    },
            //    new go.Binding("text", "default", s => s ? " = " + s : "")),
            {
                contextMenu: contextMenuOfProperty,
                mouseEnter: (e, obj) => highlight(obj, true),
                mouseLeave: (e, obj) => highlight(obj, false)    
            }                 
        );



    function parameter1Name(){
        return $(go.TextBlock, textBlockCommon(true, UML_METHOD_NAME_COLOR, true), {
                mouseEnter: (e, obj) => highlight(obj, true),
                mouseLeave: (e, obj) => highlight(obj, false)    
            },
            new go.Binding("text", "param1Name").makeTwoWay()
        );
    }


    function parameter1Sep(){
        return $(go.TextBlock, "", textBlockCommon(false, UML_METHOD_NAME_COLOR, false), 
            new go.Binding("text", "param1Type", value => value ? ":" : ""));
    }


    function parameter1Type(){
        return $(go.TextBlock, textBlockCommon(false, UML_DATA_TYPE_COLOR, false), {
                contextMenu: contextMenuOfTypes("param1Type"),
                mouseEnter: (e, obj) => highlight(obj, true),
                mouseLeave: (e, obj) => highlight(obj, false)    
            },
            new go.Binding("text", "param1Type").makeTwoWay()
        );
    }


    // the item template for methods
    var methodTemplate =
        $(go.Panel, "Horizontal",
            // method visibility/access
            $(go.TextBlock, textBlockCommon(false, UML_VISIBILITY_CHAR_COLOR, false), {
                    width: 12,
                    mouseEnter: (e, obj) => highlight(obj, true),
                    mouseLeave: (e, obj) => highlight(obj, false)    
                },
                new go.Binding("text", "visibility", convertVisibility)),
            // method name, underlined if scope=="class" to indicate static method
            $(go.TextBlock, textBlockCommon(true, UML_METHOD_NAME_COLOR, true), 
                new go.Binding("text", "name").makeTwoWay(),
                new go.Binding("isUnderline", "scope", s => s[0] === 'c'), {
                    mouseEnter: (e, obj) => highlight(obj, true),
                    mouseLeave: (e, obj) => highlight(obj, false)    
                }
            ),
            $(go.TextBlock, "(", textBlockCommon(false, UML_METHOD_NAME_COLOR, false)),
            // method parameters
        
            //parameter1Name(),
            //parameter1Sep(),
            //parameter1Type(),

            // method return type, if any
            $(go.TextBlock, "", textBlockCommon(false, UML_METHOD_NAME_COLOR, false),
                new go.Binding("text", "type", t => t ? "): " : ")")),
            $(go.TextBlock, textBlockCommon(false, UML_DATA_TYPE_COLOR, false), {
                    contextMenu: contextMenuOfTypes("type"),
                    mouseEnter: (e, obj) => highlight(obj, true),
                    mouseLeave: (e, obj) => highlight(obj, false)    
                },
                new go.Binding("text", "type").makeTwoWay()),
            {
                contextMenu: contextMenuOfProperty,
                mouseEnter: (e, obj) => highlight(obj, true),
                mouseLeave: (e, obj) => highlight(obj, false)    
            }                 
        );


    myDiagram.nodeTemplate =
        $(go.Node, "Auto", 
            {
                locationSpot: go.Spot.Center,
                fromSpot: go.Spot.AllSides, 
                toSpot: go.Spot.AllSides,       
                resizable: false,               
                rotatable: false
            },
            new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify), {
                selectable: true,
                selectionAdornmentTemplate: nodeSelectionAdornmentTemplate
            }, 
            new go.Binding("angle").makeTwoWay(),
            $(go.Shape, "RoundedRectangle",
                {
                    fill: UML_CLASS_COLOR_BACKGROUND,
                    strokeWidth: 2
                }),
            // the main object is a Panel that surrounds a TextBlock with a Shape

            $(go.Panel, "Table", 
                {
                    defaultRowSeparatorStroke: "black"
                },
                // header
                $(go.TextBlock, {
                        row: 0,
                        columnSpan: 2,
                        margin: 3,
                        alignment: go.Spot.Center,
                        font: "bold 12pt sans-serif",
                        isMultiline: false,
                        editable: true,
                        background: UML_CLASS_COLOR_BACKGROUND
                    },
                    new go.Binding("text", "name").makeTwoWay()),
                // properties
                $(go.Panel, "Vertical",
                    new go.Binding("itemArray", "properties"), {
                        row: 1,
                        margin: 3,
                        stretch: go.GraphObject.Fill,
                        defaultAlignment: go.Spot.Left,
                        background: UML_CLASS_COLOR_BACKGROUND,
                        itemTemplate: propertyTemplate
                    }
                ),
                // methods
                $(go.Panel, "Vertical", 
                    new go.Binding("itemArray", "methods"), {
                        row: 2,
                        margin: 3,
                        stretch: go.GraphObject.Fill,
                        defaultAlignment: go.Spot.Left,
                        background: UML_CLASS_COLOR_BACKGROUND,
                        itemTemplate: methodTemplate
                    }
                )
            ),
            // four small named ports, one on each side:
            makePort("T", go.Spot.Top, true, true),
            makePort("L", go.Spot.Left, true, true),
            makePort("R", go.Spot.Right, true, true),
            makePort("B", go.Spot.Bottom, true, true), 
            { // handle mouse enter/leave events to show/hide the ports
                mouseEnter: (e, node) => {
                    showSmallPorts(node, true);
                },
                mouseLeave: (e, node) => showSmallPorts(node, false),
                contextMenu: contextMenuOfClass
            }
        );


    var linkSelectionAdornmentTemplate =
        $(go.Adornment, "Link",
            $(go.Shape,
                // isPanelMain declares that this Shape shares the Link.geometry
                {
                    isPanelMain: true,
                    fill: null,
                    stroke: SELECTION_COLOR,
                    strokeWidth: 0
                }) // use selection object's strokeWidth
        );


        function convertToStrokeDashArray(relationType){
            switch (relationType) {
                case UML_RELATION_TYPES[2]: return [5, 5];
                case UML_RELATION_TYPES[3]: return [5, 5];
                default: return null;
            }
        }

        function convertFromArrow(relationType) {
            switch (relationType) {
                case UML_RELATION_TYPES[4]: return "StretchedDiamond";
                case UML_RELATION_TYPES[5]: return "StretchedDiamond";
                default: return "";
            }
        }

        function convertToArrow(relationType) {
            switch (relationType) {
                case UML_RELATION_TYPES[1]: return "Triangle";
                case UML_RELATION_TYPES[2]: return "Triangle";
                case UML_RELATION_TYPES[3]: return "Boomerang";
                default: return "";
            }
        }

        function convertFill(relationType) {
            switch (relationType) {
                case UML_RELATION_TYPES[2]: return "black";
                case UML_RELATION_TYPES[3]: return "black";
                case UML_RELATION_TYPES[5]: return "black";
                default: return "white";
            }
        }


    myDiagram.linkTemplate =
        $(go.Link, // the whole link panel
            {
                selectable: true,
                selectionAdornmentTemplate: linkSelectionAdornmentTemplate,
                relinkableFrom: true,
                relinkableTo: true,
                reshapable: true,
                routing: go.Link.AvoidsNodes,
                curve: go.Link.JumpOver,
                corner: 15,
                toShortLength: 4,
                contextMenu: contextMenuOfLink,
            },
            new go.Binding("points").makeTwoWay(),
            $(go.Shape, { isPanelMain: true, strokeWidth: 2 },
                new go.Binding("strokeDashArray", "relationType", convertToStrokeDashArray)
            ),
            $(go.Shape, { scale: 2, toArrow: '' }, 
                new go.Binding("toArrow", "relationType", convertToArrow),
                new go.Binding("fill", "relationType", convertFill)
            ),
            $(go.Shape, { scale: 2, fromArrow: '' },
                new go.Binding("fromArrow", "relationType", convertFromArrow),
                new go.Binding("fill", "relationType", convertFill)
            ),
            $(go.Panel, "Auto",
                new go.Binding("visible", true).ofObject(),  
                $(go.Shape, "RoundedRectangle", {   // the link shape
                    fill: UML_RELATION_COLOR,  
                    stroke: 'black'    
                }),
                $(go.TextBlock, {
                        //text: "asociación",
                        textAlign: "center",
                        font: "8pt helvetica, arial, sans-serif",
                        stroke: "white", 
                        margin: 1,
                        minSize: new go.Size(10, NaN),
                        editable: false
                    },
                    new go.Binding("text", "relationType").makeTwoWay()
                )
            )
        );

    load(); // load an initial diagram from some JSON text
}



// This common function is called both when showing the PDF in an iframe and when downloading a PDF file.
// The options include:
//   "pageSize", either "A4" or "LETTER" (the default)
//   "layout", either "portrait" (the default) or "landscape"
//   "margin" for the uniform page margin on each page (default is 36 pt)
//   "padding" instead of the Diagram.padding when adjusting the Diagram.documentBounds for the area to render
//   "imgWidth", size of diagram image for one page; defaults to the page width minus margins
//   "imgHeight", size of diagram image for one page; defaults to the page height minus margins
//   "imgResolutionFactor" for how large the image should be scaled when rendered for each page;
//     larger is better but significantly increases memory usage (default is 3)
//   "parts", "background", "showTemporary", "showGrid", all are passed to Diagram.makeImageData
function generatePdf(action, diagram, options) {
    if (!(diagram instanceof go.Diagram)) throw new Error("no Diagram provided when calling generatePdf");
    if (!options) options = {};

    var pageSize = options.pageSize || "LETTER";
    pageSize = pageSize.toUpperCase();
    if (pageSize !== "LETTER" && pageSize !== "A4") throw new Error("unknown page size: " + pageSize);
    // LETTER: 612x792 pt == 816x1056 CSS units
    // A4: 595.28x841.89 pt == 793.71x1122.52 CSS units
    var pageWidth = (pageSize === "LETTER" ? 612 : 595.28) * 96 / 72; // convert from pt to CSS units
    var pageHeight = (pageSize === "LETTER" ? 792 : 841.89) * 96 / 72;

    var layout = options.layout || "portrait";
    layout = layout.toLowerCase();
    if (layout !== "portrait" && layout !== "landscape") throw new Error("unknown layout: " + layout);
    if (layout === "landscape") {
        var temp = pageWidth;
        pageWidth = pageHeight;
        pageHeight = temp;
    }

    var margin = options.margin !== undefined ? options.margin : 36; // pt: 0.5 inch margin on each side
    var padding = options.padding !== undefined ? options.padding : diagram.padding; // CSS units

    var imgWidth = options.imgWidth !== undefined ? options.imgWidth : (pageWidth - margin / 72 * 96 * 2); // CSS units
    var imgHeight = options.imgHeight !== undefined ? options.imgHeight : (pageHeight - margin / 72 * 96 * 2); // CSS units
    var imgResolutionFactor = options.imgResolutionFactor !== undefined ? options.imgResolutionFactor : 3;

    var pageOptions = {
        size: pageSize,
        margin: margin, // pt
        layout: layout
    };

    require(["blob-stream", "pdfkit"], (blobStream, PDFDocument) => {
        var doc = new PDFDocument(pageOptions);
        var stream = doc.pipe(blobStream());
        var bnds = diagram.documentBounds;

        // add some descriptive text
        //doc.text(diagram.nodes.count + " nodes, " + diagram.links.count + " links  Diagram size: " + bnds.width.toFixed(2) + " x " + bnds.height.toFixed(2));

        var db = diagram.documentBounds.copy().subtractMargin(diagram.padding).addMargin(padding);
        var p = db.position;
        // iterate over page areas of document bounds
        for (var j = 0; j < db.height; j += imgHeight) {
            for (var i = 0; i < db.width; i += imgWidth) {

                // if any page has no Parts partially or fully in it, skip rendering that page
                var r = new go.Rect(p.x + i, p.y + j, imgWidth, imgHeight);
                if (diagram.findPartsIn(r, true, false).count === 0) continue;

                if (i > 0 || j > 0) doc.addPage(pageOptions);

                var makeOptions = {};
                if (options.parts !== undefined) makeOptions.parts = options.parts;
                if (options.background !== undefined) makeOptions.background = options.background;
                if (options.showTemporary !== undefined) makeOptions.showTemporary = options.showTemporary;
                if (options.showGrid !== undefined) makeOptions.showGrid = options.showGrid;
                makeOptions.scale = imgResolutionFactor;
                makeOptions.position = new go.Point(p.x + i, p.y + j);
                makeOptions.size = new go.Size(imgWidth * imgResolutionFactor, imgHeight * imgResolutionFactor);
                makeOptions.maxSize = new go.Size(Infinity, Infinity);

                var imgdata = diagram.makeImageData(makeOptions);
                doc.image(imgdata, {
                    scale: 1 / (imgResolutionFactor * 96 / 72)
                });
            }
        }

        doc.end();
        stream.on('finish', () => action(stream.toBlob('application/pdf')));
    });
}



// Two different uses of generatePdf: one shows the PDF document in the page,
// the other downloads it as a file and the user specifies where to save it.

var pdfOptions = // shared by both ways of generating PDF
    {
        showTemporary: true, // default is false
        // layout: "landscape",  // instead of "portrait"
        // pageSize: "A4"        // instead of "LETTER"
    };



function downloadPdf() {
    generatePdf(blob => {
        var datauri = window.URL.createObjectURL(blob);
        var a = document.createElement("a");
        a.style = "display: none";
        a.href = datauri;
        a.download = "myDiagram.pdf";

        document.body.appendChild(a);
        requestAnimationFrame(() => {
            a.click();
            window.URL.revokeObjectURL(datauri);
            document.body.removeChild(a);
        });
    }, myDiagram, pdfOptions);
}



function diagram_check(course_id, nactivity_id, user_email) {        
    saveDiagramProperties(); // do this first, before writing to JSON
    let dataToSend = {
        course_id: course_id,
        nactivity_id: nactivity_id,
        user_email: user_email
    }
    //var mensaje="Muy Bien";
    
    alert('Checando......');
    dataToSend.diagramModel = JSON.parse(myDiagram.model.toJson());    
    dataToSendAsJson = JSON.stringify(dataToSend);
    $('#modalTitle').text('Evaluando Diagrama...');
    $('#modalResult').text("");
    $('#modalHeader').css("background-color", "white");
    $('#modalBody').css("background-color", "white");
    $('#modalFooter').css("background-color", "white");
    $.ajax({
        url: "handler_diagram_check.php",
        type: "POST",
        data: dataToSendAsJson,
        contentType: "application/json",
        success: function(dataJson){
            //Convierte el string JSON en un objeto JAVA.
            data = JSON.parse(dataJson).data;
            console.log("El resultado de la evaluación:\n"+JSON.stringify(data));
            if (data.length > 0){
                list = '<b>Problemas encontrados:</b><br>';
                data.forEach(element => {
                    list += element + "<br>";
                });
                $('#modalTitle').html('<b>ERROR</b>');
                $('#modalResult').html(list);
                $('#modalHeader').css("background-color", "#CD5C5C");
                $('#modalBody').css("background-color", "#FFDAB9");
                $('#modalFooter').css("background-color", "#CD5C5C");
                report_verb(
                    course_id,
                    nactivity_id,
                    user_email,
                    'failed'
                );
            }else{
                $.ajax({
                    url: "mensaje.php",
                    type: "POST",
                    data: "nactivity_id="+nactivity_id,
                    success: function (mensaje) {
                        $('#modalTitle').html('<b>'+mensaje+'</b>');
                        $('#modalResult').text('El Diagrama UML se corresponde con la descripción de la actividad.');
                        $('#modalHeader').css("background-color", "#3CB371");
                        $('#modalBody').css("background-color", "#98FB98");
                        $('#modalFooter').css("background-color", "#3CB371");
                        report_verb(
                            course_id,
                            nactivity_id,
                            user_email,
                            'passed'
                        );
                        report_verb(
                            course_id,
                            nactivity_id,
                            user_email,
                            'completed'
                        );
                    },
                });
                
            }
        },
        error: function(request,error){
            const msg = "Error enviando el diagrama al servidor.";
            console.log(msg);
            $('#modalTitle').html('<b>LO SENTIMOS</b>');
            $('#modalResult').html('No se pudo procesar su respuesta.<br>Revise la selección de curso y actividad.');
            $('#modalHeader').css("background-color", "white");
            $('#modalBody').css("background-color", "white");
            $('#modalFooter').css("background-color", "white");
        }

    });

}

function diagram_save(course_id, nactivity_id, user_email) {    
    saveDiagramProperties(); // do this first, before writing to JSON
    let dataToSend = {
        course_id: course_id,
        nactivity_id: nactivity_id,
        user_email: user_email
    }
    alert('salvando......');
    dataToSend.diagramModel = JSON.parse(myDiagram.model.toJson());    
    dataToSendAsJson = JSON.stringify(dataToSend);
    var home = window.location.hostname;
    var port = window.location.port;
    $('#modalTitle').text('Evaluando Diagrama...');
    $('#modalResult').text("");
    $('#modalHeader').css("background-color", "white");
    $('#modalBody').css("background-color", "white");
    $('#modalFooter').css("background-color", "white");
    $.ajax({
        url: "handler_diagram_save.php",
        type: "POST",
        data: dataToSendAsJson,
        contentType: "application/json",
        success: function(dataJson){
            //Convierte el string JSON en un objeto JAVA.
            data = JSON.parse(dataJson).data;
            console.log("El resultado de la evaluación:\n"+JSON.stringify(data));
            if (data.length > 0){
                list = '<b>Problemas encontrados:</b><br>';
                data.forEach(element => {
                    list += element + "<br>";
                });
                $('#modalTitle').html('<b>ERROR</b>');
                $('#modalResult').html(list);
                $('#modalHeader').css("background-color", "#CD5C5C");
                $('#modalBody').css("background-color", "#FFDAB9");
                $('#modalFooter').css("background-color", "#CD5C5C");
                report_verb(
                    course_id,
                    nactivity_id,
                    user_email,
                    'failed'
                );
            }else{
                $.ajax({
                    url: "mensaje.php",
                    type: "POST",
                    data: "nactivity_id="+nactivity_id,
                    success: function (mensaje) {
                        $('#modalTitle').html('<b>'+mensaje+'</b>');
                        $('#modalResult').text('El Diagrama UML se corresponde con la descripción de la actividad.');
                        $('#modalHeader').css("background-color", "#3CB371");
                        $('#modalBody').css("background-color", "#98FB98");
                        $('#modalFooter').css("background-color", "#3CB371");
                        report_verb(
                            course_id,
                            nactivity_id,
                            user_email,
                            'passed'
                        );
                        report_verb(
                            course_id,
                            nactivity_id,
                            user_email,
                            'completed'
                        );
                    },
                });
                
            }
        },
        error: function(request,error){
            const msg = "Error enviando el diagrama al servidor.";
            console.log(msg);
            $('#modalTitle').html('<b>LO SENTIMOS</b>');
            $('#modalResult').html('No se pudo procesar su respuesta.<br>Revise la selección de curso y actividad.');
            $('#modalHeader').css("background-color", "white");
            $('#modalBody').css("background-color", "white");
            $('#modalFooter').css("background-color", "white");
        }

    });

}



function diagram_insert(course_id, nactivity_id, modeEdit) {    
    saveDiagramProperties(); // do this first, before writing to JSON
    let dataToSend = {
        course_id: course_id,
        nactivity_id: nactivity_id,
        edit: modeEdit
    }
    //alert('Insertando......');
    dataToSend.diagramModel = JSON.parse(myDiagram.model.toJson());    
    dataToSendAsJson = JSON.stringify(dataToSend);
    var home = window.location.hostname;
    var port = window.location.port;
    $('#modalInsertTitle').text('Insertando Diagrama...');
    $('#modalInsertResult').text("");
    $('#modalInsertHeader').css("background-color", "white");
    $('#modalInsertBody').css("background-color", "white");
    $('#modalInsertFooter').css("background-color", "white");
    $.ajax({
        url: 'handler_diagram_insert.php',
        type: "POST",
        data: dataToSendAsJson,
        contentType: "application/json",
        success: function(dataJson){
            //Convierte el string JSON en un objeto JAVA.
            data = JSON.parse(dataJson).data;
            //alert(data);
            console.log("Se ha insertado el diagrama, OK.");
            $('#modalInsertTitle').html('<b>Diagrama Agregado</b>');
            $('#modalInsertResult').text('El Diagrama UML se agregó correctamente a la actividad.');
            $('#modalInsertHeader').css("background-color", "#98FB98");
            $('#modalInsertBody').css("background-color", "white");
            $('#modalInsertFooter').css("background-color", "#98FB98");
        },
        error: function(request,error){
            const msg = "Error insertado el diagrama.";
            console.log(msg);
            $('#modalInsertTitle').html('<b>LO SENTIMOS</b>');
            $('#modalInsertResult').html('No se pudo agregar su diagrama.<br>Revise la selección de curso y actividad.');
            $('#modalInsertHeader').css("background-color", "#FFDAB9");
            $('#modalInsertBody').css("background-color", "white");
            $('#modalInsertFooter').css("background-color", "#FFDAB9");
        }
    });

}


async function finished() {
    const data = myDiagram.model.toJson();

    if (document.getElementById('user').value === undefined) {
        alert('Debe iniciar sesión en el sistema');
    } else {
        var course = document.getElementById('course').value;
        var nactivity = document.getElementById('activity').value;
        var action = document.getElementById('user').value;
        const payload = {
            action,
            course,
            nactivity,
            data: data
        };
        const response = await fetch('http://' + window.location.hostname + ':' + window.location.port +'/xapi/newmodeldiagram', {
            method: 'POST',
            body: JSON.stringify(payload),
            headers: {
                "Content-type": "application/json; charset=UTF-8"
            },
        }).then(function(response) {
            if (response.ok) {
                window.location.replace('http://' + window.location.hostname + ':' + window.location.port + '/model/diagram/test/')
            } else {
                window.alert('Error en los datos');
            }
        })

    }
}



function leave() {
    var home = window.location.hostname;
    var port = window.location.port;
    window.location.replace('http://' + home + ':' + port+'/model/diagram/test/');
}

function load() {
    let dataJsonDiagram = document.getElementById("dataJsonDiagram").innerText;
    myDiagram.model = go.Model.fromJson(dataJsonDiagram);
    loadDiagramProperties(); // do this after the Model.modelData has been brought into memory
}

function saveDiagramProperties() {
    myDiagram.model.modelData.position = go.Point.stringify(myDiagram.position);
}

function loadDiagramProperties(e) {
    // set Diagram.initialPosition, not Diagram.position, to handle initialization side-effects
    var pos = myDiagram.model.modelData.position;
    if (pos) myDiagram.initialPosition = go.Point.parse(pos);
}

window.addEventListener('DOMContentLoaded', init);

/**
 * Agrega una nueva clase UML en el diagrama.
 * @param x - Coordenada X a partir de donde se crea el objeto clase.
 * @param y - Coordenada Y a partir de donde se crea el objeto clase.
 */
function addNewClass(x, y){
    myDiagram.model.addNodeData({
        name: "Nombre de clase",
        properties: [
            {
                name: "propiedad",
                type: "tipo",
                visibility: "public"
            }
        ],
        methods: [{
            name: "método",
            type: "tipo",
            visibility: "public",
            param1Name: "parámetro", 
            param1Type: "tipo"
        }],
        loc: x+" "+y
    });
}

/**
 * Agrega una nueva Relación en el diagrama, en la posición indicada.
 * @param x - Coordenada X a partir de donde se crea el objeto Relación.
 * @param y - Coordenada Y a partir de donde se crea el objeto Relación.
 */
function addNewLink(x, y){
    myDiagram.model.addLinkData({
        fromPort: "",
        toPort: "",
        "points":[x, y, x+100,y+100], 
        relationType: UML_RELATION_TYPES[0]
    });
}

/** Para deshacer los ultimos cambios realizados en el diagrama */
function undoDiagramChanges(){
    myDiagram.commandHandler.undo();
}

/** Para rehacer los ultimos cambios retrocedidos en el diagrama */
function redoDiagramChanges(){
    myDiagram.commandHandler.redo();
}


/** Elimina todos los objeto del diagrama */
function clearDiagram(){
    myDiagram.clear();
}



function report_verb(course_id, nactivity_id, user_email, verb) {        
    saveDiagramProperties(); // do this first, before writing to JSON
    let dataToSend = {
        course_id: course_id,
        nactivity_id: nactivity_id,
        user_email: user_email,
        verb: verb
    }
    dataToSend.diagramModel = JSON.parse(myDiagram.model.toJson());    
    dataToSendAsJson = JSON.stringify(dataToSend);
    $.ajax({
        url: "handle_cmi5_report_verb.php",
        type: "POST",
        data: dataToSendAsJson,
        contentType: "application/json",
        success: function(dataJson){
            console.log('Verbo cmi5: ', verb);
        },
        error: function(request,error){
            console.log('Error reportando verbo cmi5: ', verb);
        }
    });

}


