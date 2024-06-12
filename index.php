<!DOCTYPE html>
<html>
<?php 
    $ruta = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Refresh" content="0; url='/public/index.php/login'" />
    <link rel="icon" type="image/x-icon" href="<?php echo $ruta.'/dist/img/XperienceUML.png';?>" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    <title>Editor de Diagrama V1</title>
    <div class="content-wrapper">


        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                    <div class="col-md-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a  href="<?php echo $ruta;?>">Inicio</a></li>
                            <li class="breadcrumb-item active">Editor Diagrama</li>
                        </ol>
                    </div>
                </div>

            </div>

            <div class="container" style="margin-block-end: 30px;">
                <h3>Nuevo Modelo de Diagrama a Probar</h3>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <?php
                    $arrContextOptions = array(
                        "ssl" => array(
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                        ),
                    );
                    session_start();
                    $user_by_email = $_SESSION["_sf2_attributes"];
                    if (array_key_exists('_security.last_username', $user_by_email)) {
                        $user_by_email = $_SESSION["_sf2_attributes"]['_security.last_username'];
                    } else {
                        $user_by_email = 'None';
                    }


                    $response_course = json_decode(file_get_contents('http://localhost/v2cristian/public/index.php/xapi/course'), true);
                    $response_activity = json_decode(file_get_contents('http://localhost/v2cristian/public/index.php/xapi/modelActivity'), true);


                    ?>
                    <?php if ($user_by_email == 'None') :
                        echo '<h3> Debe iniciar sesión primero'; ?>

                    <?php
                    else :
                    ?>
                        <div class="col-md-4">
                            <?= '<input type="hidden" value=' . $user_by_email . ' id="user">' ?>
                            <label for="course">Curso:</label>
                            <select name="course" id="course" required>
                                <?php foreach ($response_course as $resp) {
                                    echo '<option value=' . $resp['id'] . '>' . $resp['name'] . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="activity">Actividad:</label>
                            <select name="activity" id="activity">
                                <?php foreach ($response_activity as $resp) {
                                    echo '<option value=' . $resp['id'] . '>' . substr($resp['title'], 0, 11) . '</option>';
                                } ?>
                            </select>
                        </div>
                </div>
            </div>
        </section>
        <div id="allSampleContent" class="p-4 w-full">
            <script id="code">
                function init() {

                    // Since 2.2 you can also author concise templates with method chaining instead of GraphObject.make
                    // For details, see https://gojs.net/latest/intro/buildingObjects.html
                    const $ = go.GraphObject.make;

                    myDiagram =
                        $(go.Diagram, "myDiagramDiv",


                            {
                                "undoManager.isEnabled": true,
                                "grid.visible": true,
                                layout: $(go.TreeLayout, { // this only lays out in trees nodes connected by "generalization" links
                                    angle: 90,
                                    path: go.TreeLayout.PathSource, // links go from child to parent
                                    setsPortSpot: false, // keep Spot.AllSides for link connection spot
                                    setsChildPortSpot: false, // keep Spot.AllSides
                                    // nodes not connected by "generalization" links are laid out horizontally
                                    arrangement: go.TreeLayout.ArrangementHorizontal
                                }),
                                "draggingTool.dragsLink": true,
                                "draggingTool.isGridSnapEnabled": true,
                                "linkingTool.isUnconnectedLinkValid": true,
                                "linkingTool.portGravity": 20,
                                "relinkingTool.isUnconnectedLinkValid": true,
                                "relinkingTool.portGravity": 20,
                                "relinkingTool.fromHandleArchetype": $(go.Shape, "Diamond", {
                                    segmentIndex: 0,
                                    cursor: "pointer",
                                    desiredSize: new go.Size(8, 8),
                                    fill: "tomato",
                                    stroke: "darkred"
                                }),
                                "relinkingTool.toHandleArchetype": $(go.Shape, "Diamond", {
                                    segmentIndex: -1,
                                    cursor: "pointer",
                                    desiredSize: new go.Size(8, 8),
                                    fill: "darkred",
                                    stroke: "tomato"
                                }),
                                "linkReshapingTool.handleArchetype": $(go.Shape, "Diamond", {
                                    desiredSize: new go.Size(7, 7),
                                    fill: "lightblue",
                                    stroke: "deepskyblue"
                                }),
                                "rotatingTool.handleAngle": 270,
                                "rotatingTool.handleDistance": 30,
                                "rotatingTool.snapAngleMultiple": 15,
                                "rotatingTool.snapAngleEpsilon": 15,
                                "undoManager.isEnabled": true


                            });

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

                    // the item template for properties
                    var propertyTemplate =
                        $(go.Panel, "Horizontal",
                            // property visibility/access
                            $(go.TextBlock, {
                                    isMultiline: false,
                                    editable: false,
                                    width: 12
                                },
                                new go.Binding("text", "visibility", convertVisibility)),
                            // property name, underlined if scope=="class" to indicate static property
                            $(go.TextBlock, {
                                    isMultiline: false,
                                    editable: true
                                },
                                new go.Binding("text", "name").makeTwoWay(),
                                new go.Binding("isUnderline", "scope", s => s[0] === 'c')),
                            // property type, if known
                            $(go.TextBlock, "",
                                new go.Binding("text", "type", t => t ? ": " : "")),
                            $(go.TextBlock, {
                                    isMultiline: false,
                                    editable: true
                                },
                                new go.Binding("text", "type").makeTwoWay()),
                            // property default value, if any
                            $(go.TextBlock, {
                                    isMultiline: false,
                                    editable: false
                                },
                                new go.Binding("text", "default", s => s ? " = " + s : ""))
                        );

                    // the item template for methods
                    var methodTemplate =
                        $(go.Panel, "Horizontal",
                            // method visibility/access
                            $(go.TextBlock, {
                                    isMultiline: false,
                                    editable: false,
                                    width: 12
                                },
                                new go.Binding("text", "visibility", convertVisibility)),
                            // method name, underlined if scope=="class" to indicate static method
                            $(go.TextBlock, {
                                    isMultiline: false,
                                    editable: true
                                },
                                new go.Binding("text", "name").makeTwoWay(),
                                new go.Binding("isUnderline", "scope", s => s[0] === 'c')),
                            // method parameters
                            $(go.TextBlock, "()",
                                // this does not permit adding/editing/removing of parameters via inplace edits
                                new go.Binding("text", "parameters", function(parr) {
                                    var s = "(";
                                    for (var i = 0; i < parr.length; i++) {
                                        var param = parr[i];
                                        if (i > 0) s += ", ";
                                        s += param.name + ": " + param.type;
                                    }
                                    return s + ")";
                                })),
                            // method return type, if any
                            $(go.TextBlock, "",
                                new go.Binding("text", "type", t => t ? ": " : "")),
                            $(go.TextBlock, {
                                    isMultiline: false,
                                    editable: true
                                },
                                new go.Binding("text", "type").makeTwoWay())
                        );

                    // this simple template does not have any buttons to permit adding or
                    // removing properties or methods, but it could!
                    myDiagram.nodeTemplate =
                        $(go.Node, "Auto", {
                                locationSpot: go.Spot.Center,
                                fromSpot: go.Spot.AllSides,
                                toSpot: go.Spot.AllSides
                            },

                            $(go.Shape, {
                                fill: "lightyellow"
                            }),
                            $(go.Panel, "Table", {
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
                                        editable: true
                                    },
                                    new go.Binding("text", "name").makeTwoWay()),
                                // properties
                                $(go.TextBlock, "Properties", {
                                        row: 1,
                                        font: "italic 10pt sans-serif"
                                    },
                                    new go.Binding("visible", "visible", v => !v).ofObject("PROPERTIES")),
                                $(go.Panel, "Vertical", {
                                        name: "PROPERTIES"
                                    },
                                    new go.Binding("itemArray", "properties"), {
                                        row: 1,
                                        margin: 3,
                                        stretch: go.GraphObject.Fill,
                                        defaultAlignment: go.Spot.Left,
                                        background: "lightyellow",
                                        itemTemplate: propertyTemplate
                                    }
                                ),
                                $("PanelExpanderButton", "PROPERTIES", {
                                        row: 1,
                                        column: 1,
                                        alignment: go.Spot.TopRight,
                                        visible: false
                                    },
                                    new go.Binding("visible", "properties", arr => arr.length > 0)),
                                // methods
                                $(go.TextBlock, "Methods", {
                                        row: 2,
                                        font: "italic 10pt sans-serif"
                                    },
                                    new go.Binding("visible", "visible", v => !v).ofObject("METHODS")),
                                $(go.Panel, "Vertical", {
                                        name: "METHODS"
                                    },
                                    new go.Binding("itemArray", "methods"), {
                                        row: 2,
                                        margin: 3,
                                        stretch: go.GraphObject.Fill,
                                        defaultAlignment: go.Spot.Left,
                                        background: "lightyellow",
                                        itemTemplate: methodTemplate
                                    }
                                ),
                                $("PanelExpanderButton", "METHODS", {
                                        row: 2,
                                        column: 1,
                                        alignment: go.Spot.TopRight,
                                        visible: false
                                    },
                                    new go.Binding("visible", "methods", arr => arr.length > 0))
                            ),

                        );



                    function convertIsTreeLink(r) {
                        return r === "generalization";
                    }

                    function convertFromArrow(r) {
                        switch (r) {
                            case "generalization":
                                return "";
                            default:
                                return "";
                        }
                    }

                    function convertToArrow(r) {
                        switch (r) {
                            case "generalization":
                                return "Triangle";
                            case "aggregation":
                                return "StretchedDiamond";
                            default:
                                return "";
                        }
                    }

                    myDiagram.linkTemplate =
                        $(go.Link, {
                                routing: go.Link.Orthogonal
                            },
                            new go.Binding("isLayoutPositioned", "relationship", convertIsTreeLink),
                            $(go.Shape),
                            $(go.Shape, {
                                    scale: 1.3,
                                    fill: "white"
                                },
                                new go.Binding("fromArrow", "relationship", convertFromArrow)),
                            $(go.Shape, {
                                    scale: 1.3,
                                    fill: "white"
                                },
                                new go.Binding("toArrow", "relationship", convertToArrow))
                        );

                    // setup a few example class nodes and relationships
                    var nodedata = [{
                            key: 11,
                            name: "Ejemplo1",
                            properties: [{
                                name: "name",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "birth",
                                type: "Date",
                                visibility: "protected"
                            }],
                            methods: [{
                                name: "getCurrentAge",
                                type: "int",
                                visibility: "public"
                            }]
                        }, {
                            key: 12,
                            name: "Ejemplo2",
                            properties: [{
                                name: "classes",
                                type: "List<Course>",
                                visibility: "public"
                            }],
                            methods: [{
                                name: "attend",
                                parameters: [{
                                    name: "class",
                                    type: "Course"
                                }],
                                visibility: "private"
                            }, {
                                name: "sleep",
                                visibility: "private"
                            }]
                        }, {
                            key: 13,
                            name: "Ejemplo3",
                            properties: [{
                                name: "classes",
                                type: "List<Course>",
                                visibility: "public"
                            }],
                            methods: [{
                                name: "teach",
                                parameters: [{
                                    name: "class",
                                    type: "Course"
                                }],
                                visibility: "private"
                            }]
                        }, {
                            key: 14,
                            name: "Ejemplo4",
                            properties: [{
                                name: "name",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "description",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "professor",
                                type: "Professor",
                                visibility: "public"
                            }, {
                                name: "location",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "times",
                                type: "List<Time>",
                                visibility: "public"
                            }, {
                                name: "prerequisites",
                                type: "List<Course>",
                                visibility: "public"
                            }, {
                                name: "students",
                                type: "List<Student>",
                                visibility: "public"
                            }]
                        },



                        {
                            key: 15,
                            name: "Ejemplo5",
                            properties: [{
                                name: "name",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "description",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "professor",
                                type: "Professor",
                                visibility: "public"
                            }, {
                                name: "location",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "times",
                                type: "List<Time>",
                                visibility: "public"
                            }, {
                                name: "prerequisites",
                                type: "List<Course>",
                                visibility: "public"
                            }, {
                                name: "students",
                                type: "List<Student>",
                                visibility: "public"
                            }]
                        },


                        {
                            key: 16,
                            name: "Ejemplo6",
                            properties: [{
                                name: "name",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "description",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "professor",
                                type: "Professor",
                                visibility: "public"
                            }, {
                                name: "location",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "times",
                                type: "List<Time>",
                                visibility: "public"
                            }, {
                                name: "prerequisites",
                                type: "List<Course>",
                                visibility: "public"
                            }, {
                                name: "students",
                                type: "List<Student>",
                                visibility: "public"
                            }]
                        }, {
                            key: 17,
                            name: "Ejemplo7",
                            properties: [{
                                name: "name",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "description",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "professor",
                                type: "Professor",
                                visibility: "public"
                            }, {
                                name: "location",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "times",
                                type: "List<Time>",
                                visibility: "public"
                            }, {
                                name: "prerequisites",
                                type: "List<Course>",
                                visibility: "public"
                            }, {
                                name: "students",
                                type: "List<Student>",
                                visibility: "public"
                            }]
                        },


                        {
                            key: 18,
                            name: "Ejemplo8",
                            properties: [{
                                name: "name",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "description",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "professor",
                                type: "Professor",
                                visibility: "public"
                            }, {
                                name: "location",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "times",
                                type: "List<Time>",
                                visibility: "public"
                            }, {
                                name: "prerequisites",
                                type: "List<Course>",
                                visibility: "public"
                            }, {
                                name: "students",
                                type: "List<Student>",
                                visibility: "public"
                            }]
                        },

                        {
                            key: 19,
                            name: "Ejemplo9",
                            properties: [{
                                name: "name",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "description",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "professor",
                                type: "Professor",
                                visibility: "public"
                            }, {
                                name: "location",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "times",
                                type: "List<Time>",
                                visibility: "public"
                            }, {
                                name: "prerequisites",
                                type: "List<Course>",
                                visibility: "public"
                            }, {
                                name: "students",
                                type: "List<Student>",
                                visibility: "public"
                            }]
                        },


                        {
                            key: 20,
                            name: "Ejemplo10",
                            properties: [{
                                name: "name",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "description",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "professor",
                                type: "Professor",
                                visibility: "public"
                            }, {
                                name: "location",
                                type: "String",
                                visibility: "public"
                            }, {
                                name: "times",
                                type: "List<Time>",
                                visibility: "public"
                            }, {
                                name: "prerequisites",
                                type: "List<Course>",
                                visibility: "public"
                            }, {
                                name: "students",
                                type: "List<Student>",
                                visibility: "public"
                            }]
                        }




                    ];
                    var linkdata = [{
                            from: 12,
                            to: 11,
                            relationship: "generalization"
                        }, {
                            from: 13,
                            to: 11,
                            relationship: "generalization"
                        }, {
                            from: 14,
                            to: 13,
                            relationship: "aggregation"
                        }, {
                            from: 15,
                            to: 16,
                            relationship: "aggregation"
                        }, {
                            from: 16,
                            to: 17,
                            relationship: "generalization"
                        }, {
                            from: 18,
                            to: 19,
                            relationship: "generalization"
                        },

                        {
                            from: 19,
                            to: 20,
                            relationship: "aggregation"
                        },


                    ];

                    myDiagram.model = new go.GraphLinksModel({
                        copiesArrays: true,
                        copiesArrayObjects: true,
                        nodeDataArray: nodedata,
                        linkDataArray: linkdata
                    });



                    // initialize the Palette that is on the left side of the page
                    myPalette =
                        $(go.Palette, "myPaletteDiv", // must name or refer to the DIV HTML element
                            {
                                maxSelectionCount: 1,
                                nodeTemplateMap: myDiagram.nodeTemplateMap, // share the templates used by myDiagram
                                linkTemplate: // simplify the link template, just in this Palette
                                    $(go.Link, { // because the GridLayout.alignment is Location and the nodes have locationSpot == Spot.Center,
                                            // to line up the Link in the same manner we have to pretend the Link has the same location spot
                                            locationSpot: go.Spot.Center,
                                            selectionAdornmentTemplate: $(go.Adornment, "Link", {
                                                    locationSpot: go.Spot.Center
                                                },
                                                $(go.Shape, {
                                                    isPanelMain: true,
                                                    fill: null,
                                                    stroke: "deepskyblue",
                                                    strokeWidth: 0
                                                }),
                                                $(go.Shape, // the arrowhead
                                                    {
                                                        toArrow: "Standard",
                                                        stroke: null
                                                    })
                                            )
                                        }, {
                                            routing: go.Link.AvoidsNodes,
                                            curve: go.Link.JumpOver,
                                            corner: 5,
                                            toShortLength: 4
                                        },
                                        new go.Binding("points"),
                                        $(go.Shape, // the link path shape
                                            {
                                                isPanelMain: true,
                                                strokeWidth: 2
                                            }),
                                        $(go.Shape, // the arrowhead
                                            {
                                                toArrow: "Standard",
                                                stroke: null
                                            })

                                    ),
                                model: new go.GraphLinksModel([ // specify the contents of the Palette
                                        {
                                            key: 1,
                                            name: "ClaseX",
                                            properties: [{
                                                name: "x",
                                                type: "String",
                                                visibility: "public"
                                            }, {
                                                name: "name1",
                                                type: "Currency",
                                                visibility: "public",
                                                default: "0"
                                            }],
                                            methods: [{
                                                name: "operation1",
                                                parameters: [{
                                                    name: "atribute1",
                                                    type: "Currency"
                                                }],
                                                visibility: "public"
                                            }, {
                                                name: "operation2",
                                                parameters: [{
                                                    name: "atribute2",
                                                    type: "Currency"
                                                }],
                                                visibility: "public"
                                            }]
                                        },


                                        {
                                            key: 13,
                                            name: "ClaseY",
                                            properties: [{
                                                name: "classes",
                                                type: "List<Course>",
                                                visibility: "public"
                                            }],
                                            methods: [{
                                                name: "teach",
                                                parameters: [{
                                                    name: "class",
                                                    type: "Course"
                                                }],
                                                visibility: "private"
                                            }]
                                        },


                                        {
                                            key: 14,
                                            name: "ClaseZ",
                                            properties: [{
                                                    name: "prop1",
                                                    type: "String",
                                                    visibility: "public"
                                                }, {
                                                    name: "prop2",
                                                    type: "String",
                                                    visibility: "public"
                                                },

                                                {
                                                    name: "prop3",
                                                    type: "String",
                                                    visibility: "public"
                                                },

                                            ],
                                            methods: [{
                                                    name: "oper1",
                                                    parameters: [{
                                                        name: "atribute1",
                                                        type: "Boolean"
                                                    }],
                                                    visibility: "private"
                                                },

                                                {
                                                    name: "oper2",
                                                    parameters: [{
                                                        name: "atribute2",
                                                        type: "Integer"
                                                    }],
                                                    visibility: "private"
                                                },

                                                {
                                                    name: "oper3",
                                                    parameters: [{
                                                        name: "atribute3",
                                                        type: "String"
                                                    }],
                                                    visibility: "private"
                                                },

                                            ]
                                        },


                                        {
                                            key: 20,
                                            name: "ClaseW",
                                            properties: [{
                                                    name: "prop1",
                                                    type: "String",
                                                    visibility: "public"
                                                }, {
                                                    name: "prop2",
                                                    type: "String",
                                                    visibility: "public"
                                                },

                                                {
                                                    name: "prop3",
                                                    type: "String",
                                                    visibility: "public"
                                                },

                                                {
                                                    name: "prop4",
                                                    type: "String",
                                                    visibility: "public"
                                                },

                                            ],
                                            methods: [{
                                                    name: "oper1",
                                                    parameters: [{
                                                        name: "atribute1",
                                                        type: "Boolean"
                                                    }],
                                                    visibility: "private"
                                                },

                                                {
                                                    name: "oper2",
                                                    parameters: [{
                                                        name: "atribute2",
                                                        type: "Integer"
                                                    }],
                                                    visibility: "private"
                                                },

                                                {
                                                    name: "oper3",
                                                    parameters: [{
                                                        name: "atribute3",
                                                        type: "String"
                                                    }],
                                                    visibility: "private"
                                                },

                                                {
                                                    name: "oper4",
                                                    parameters: [{
                                                        name: "atribute4",
                                                        type: "String"
                                                    }],
                                                    visibility: "private"
                                                }

                                            ]
                                        },




                                    ],




                                    [

                                        // the Palette also has a disconnected Link, which the user can drag-and-drop
                                        {
                                            //points: new go.List( /*go.Point*/ ).addAll([new go.Point(0, 0), new go.Point(30, 0), new go.Point(30, 40), new go.Point(60, 40)])
                                        }
                                    ])
                            });



                    // initialize Overview
                    myOverview =
                        $(go.Overview, "myOverviewDiv", {
                            observed: myDiagram,
                            contentAlignment: go.Spot.Center
                        });

                    var inspector = new Inspector('myInspectorDiv', myDiagram, {
                        // uncomment this line to only inspect the named properties below instead of all properties on each object:
                        // includesOwnProperties: false,
                        properties: {
                            "text": {},
                            // key would be automatically added for nodes, but we want to declare it read-only also:
                            "key": {
                                readOnly: true,
                                show: Inspector.showIfPresent
                            },
                            // color would be automatically added for nodes, but we want to declare it a color also:
                            "color": {
                                type: 'color'
                            },
                            "figure": {}
                        }
                    });
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



                function showPdf() {
                    generatePdf(blob => {
                        var datauri = window.URL.createObjectURL(blob);
                        var frame = document.getElementById("myFrame");
                        if (frame) {
                            frame.style.display = "block";
                            frame.src = datauri; // doesn't work in IE 11, but works everywhere else
                            setTimeout(() => window.URL.revokeObjectURL(datauri), 1);
                        }
                    }, myDiagram, pdfOptions);
                }

                //Este es el que guarda el pdf.
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


                function addToPalette() {
                    var node = myDiagram.selection.filter(p => p instanceof go.Node).first();
                    if (node !== null) {
                        myPalette.startTransaction();
                        var item = myPalette.model.copyNodeData(node.data);
                        myPalette.model.addNodeData(item);
                        myPalette.commitTransaction("added item to palette");
                    }
                }

                // The user cannot delete selected nodes in the Palette with the Delete key or Control-X,
                // but they can if they do so programmatically.
                function removeFromPalette() {
                    myPalette.commandHandler.deleteSelection();
                }



                // Show the diagram's model in JSON format that the user may edit
                function save() {

                    document.getElementById("mySavedModel").value = myDiagram.model.toJson();
                    myDiagram.isModified = false;
                }

                function load() {
                    myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
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
                        const response = await fetch('http://localhost/v2cristian/public/index.php/xapi/newmodeldiagram', {
                            method: 'POST',
                            body: JSON.stringify(payload),
                            headers: {
                                "Content-type": "application/json; charset=UTF-8"
                            },
                        }).then(function(response) {
                            if (response.ok) {
                                window.location.replace("http://localhost/v2cristian/public/index.php/model/diagram/test/")
                            }
                            else{
                                window.alert('Error en los datos');
                            }
                        })

                        /*.then(response => response.json()).then(json => console.log(json))
                        .catch(err => console.log(err));*/

                    }
                }

                function leave() {
                    window.location.replace("http://localhost/v2cristian/public/index.php/model/diagram/test/");
                }




                window.addEventListener('DOMContentLoaded', init);
            </script>
        </div>
    </div>

<body onload="init()">
    <div>
        <div>
            <button class="btn btn-success" id="SaveButton" onclick="save()"><strong>Salvar</strong></button>
            <button class="btn btn-info" onclick="load()"><strong>Cargar</strong></button>
            <button class='btn btn-primary' id="finished" onclick="finished()"><strong>Entregar y Finalizar</strong></button>
            <button class='btn btn-danger' id="leave" onclick="leave()"><strong>Abandonar</strong></button>
            Modelo de Diagrama salvado a formato JSON :
        </div>
        <textarea id="mySavedModel" style="width:100%;height:300px">
    { "class": "go.GraphLinksModel",
      "copiesArrays": "true",
      "copiesArrayObjects": "true",
      "nodeDataArray": "nodedata",
      "linkDataArray": "linkdata",
      "nodeDataArray":
      [{
        key: 11,
        name: "Ejemplo1",
        properties: [{
            name: "name",
            type: "String",
            visibility: "public"
        }, {
            name: "birth",
            type: "Date",
            visibility: "protected"
        }],
        methods: [{
            name: "getCurrentAge",
            type: "int",
            visibility: "public"
        }]
    }, {
        key: 12,
        name: "Ejemplo2",
        properties: [{
            name: "classes",
            type: "List<Course>",
            visibility: "public"
        }],
        methods: [{
            name: "attend",
            parameters: [{
                name: "class",
                type: "Course"
            }],
            visibility: "private"
        }, {
            name: "sleep",
            visibility: "private"
        }]
    }, {
        key: 13,
        name: "Ejemplo3",
        properties: [{
            name: "classes",
            type: "List<Course>",
            visibility: "public"
        }],
        methods: [{
            name: "teach",
            parameters: [{
                name: "class",
                type: "Course"
            }],
            visibility: "private"
        }]
    }, {
        key: 14,
        name: "Ejemplo4",
        properties: [{
            name: "name",
            type: "String",
            visibility: "public"
        }, {
            name: "description",
            type: "String",
            visibility: "public"
        }, {
            name: "professor",
            type: "Professor",
            visibility: "public"
        }, {
            name: "location",
            type: "String",
            visibility: "public"
        }, {
            name: "times",
            type: "List<Time>",
            visibility: "public"
        }, {
            name: "prerequisites",
            type: "List<Course>",
            visibility: "public"
        }, {
            name: "students",
            type: "List<Student>",
            visibility: "public"
        }]
    }];
    var linkdata = [{
        from: 12,
        to: 11,
        relationship: "generalization"
    }, {
        from: 13,
        to: 11,
        relationship: "generalization"
    }, {
        from: 14,
        to: 13,
        relationship: "aggregation"
    }]








    }
        </textarea>
    </div>
    <div id="sample">





        <div style="width:100%; white-space:nowrap;">
            <span style="display: inline-block; vertical-align: top; padding: 2px; width:300px">

                <div id="myPaletteDiv" style="background-color: whitesmoke; border: solid 1px black; height: 1000px"></div>
                <div id="myOverviewDiv" style="border: solid 1px black; height: 100px"></div>
            </span>
            <span style="display: inline-block; vertical-align: top; padding: 2px; width:1500px">
                <div id="myDiagramDiv" style="border: solid 1px black; height: 1000px"></div>

            </span>
            <span style="display: inline-block; vertical-align: top; padding: 2px; width:px">
                <div id="myInspectorDiv" class="inspector"></div>
            </span>
        </div>
        <div><button onclick="showPdf()"><strong>Ver PDF</strong></button> <button onclick="downloadPdf()"><strong>Descargar PDF</strong></button>
            <button onclick="addToPalette()"><strong>Agregar a la Paleta</strong></button>
            <button onclick="removeFromPalette()"><strong>Eliminar de la Paleta</strong></button>

        </div>
        <iframe id="myFrame" style="display:none; width:1000px; height:1000px"></iframe>
    </div>
<?php endif ?>
</body>

<p class="text-xs">Editor v1.</p>
<script src="https://unpkg.com/gojs"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.min.js"></script>

</html>