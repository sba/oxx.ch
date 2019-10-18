requirejs.config({paths:{jquery:"lib/jquery-1.8.0",jqueryUI:"lib/jquery.ui.widget",json:typeof JSON=="undefined"?"lib/json2":"empty:",underscore:"lib/underscore-1.3.3",backbone:"lib/backbone-0.9.2",backboneSubroute:"lib/backbone-subroute-0.3.2",dust:"lib/dust-core-2.0.3",nougat:"core/nougat",BaseView:"core/baseView","dust-helpers":"lib/dust-helpers-1.1.1","dust-helpers-supplement":"lib/dust-helpers-supplement","fso-helper":"lib/fso-helper",fso:"lib/fso",browserID:"lib/bid",lap:"components/textInput/lap",textField:"components/textInput/textField",numeric:"components/textInput/numeric",nativeDropdown:"components/dropdown/nativeDropdown",creditcard:"components/creditcard/creditcard",passwordField:"components/password/passwordField",phoneNumber:"components/format/phoneNumber",buttonToggle:"components/button/buttonToggle",pinField:"components/pin/pinField",dob:"components/format/dob",sortCode:"components/format/sortCode",restrict:"components/textInput/restrict",custom:"components/format/custom",routingNumber:"components/format/routingNumber"},useStrict:!0,shim:{dust:{exports:"dust"},"dust-helpers":{deps:["dust"]},"dust-helpers-supplement":{deps:["dust","dust-helpers"]},jqueryUI:{deps:["jquery"]},underscore:{exports:"_"},backbone:{deps:["underscore","jquery"],exports:"Backbone"},backboneSubroutes:{deps:["backbone"]},lap:{deps:["jquery","jqueryUI"]},textField:{deps:["jquery","jqueryUI"]},nativeDropdown:{deps:["jquery"]},creditcard:{deps:["jquery","jqueryUI"]},passwordField:{deps:["jquery","jqueryUI"]},numeric:{deps:["jquery","jqueryUI"]},phoneNumber:{deps:["jquery","jqueryUI"]},buttonToggle:{deps:["jquery"]},pinField:{deps:["jquery","jqueryUI"]},dob:{deps:["jquery","jqueryUI"]},sortCode:{deps:["jquery","jqueryUI"]},restrict:{deps:["jquery","jqueryUI"]},custom:{deps:["jquery","jqueryUI"]},routingNumber:{deps:["jquery","jqueryUI"]}}}),define("config",function(){}),require(["config"],function(e){"use strict";require(["router","widgets/analytics"],function(e,t){var n={initialize:function(){t.initialize()}};n.initialize()})}),define("app",function(){});