(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-d672a16a"],{"280c":function(a,t,e){},b3d1:function(a,t,e){"use strict";var n=e("280c"),s=e.n(n);s.a},b858:function(a,t,e){"use strict";e.r(t);var n=function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("section",{staticClass:"clean-block p-0 py-5 dark",style:a.backGround,attrs:{id:"background-img"}},[e("b-container",[e("div",{staticClass:"d-flex justify-content-between"},[e("h2",{staticStyle:{color:"white","text-shadow":"1px 2px black"}},[a._v(a._s(a.branchName))]),e("b-btn",{staticClass:"mb-2",attrs:{href:a.branchName+"/inschrijven",variant:"primary"}},[a._v("Inschrijven")])],1),e("b-card",{staticClass:"clean-card"},[e("quillViewer",{attrs:{ready:a.returned,quill:a.branch.branchDescription}}),a.returned&&a.branch.albums.length>0?e("div",[e("hr"),e("h2",[a._v("Foto's")]),e("albumViewer",{attrs:{albums:a.branch.albums}})],1):a._e(),e("hr",{staticClass:"mt-5"}),e("h2",[a._v("Kalender")]),e("calendarViewer",{attrs:{ready:a.calendarReturned,events:a.events}}),e("div",{staticClass:"d-flex mt-4"},[e("ul",{staticClass:"list-inline social-buttons mx-auto"},[""!=a.branch.instaUsername?e("li",{staticClass:"list-inline-item"},[e("a",{staticClass:"social-link",attrs:{href:"https://www.instagram.com/"+a.branch.instaUsername}},[e("i",{staticClass:"fab fa-instagram fa-2x"})])]):a._e(),""!=a.branch.facebookUsername?e("li",{staticClass:"list-inline-item"},[e("a",{staticClass:"social-link",attrs:{href:"https://www.facebook.com/"+a.branch.facebookUsername}},[e("i",{staticClass:"fab fa-facebook fa-2x"})])]):a._e()])]),e("div",{staticClass:"fb-comments",attrs:{"data-href":"http://www.scoutinghuissenzand.nl/#scoutinghuissenzand"+a.branchName,"data-numposts":"10","data-width":"100%"}})],1)],1)],1)},s=[],r=(e("4160"),e("159b"),e("56d7")),c=e("2b0e"),i=e("be3b"),o=e("a18c"),l=e("7210"),b=e("cf58"),d=e("1288"),u={name:"branch",props:["branchName"],components:{calendarViewer:b["a"],quillViewer:l["a"],albumViewer:d["a"]},data:function(){return{backGround:"",branch:{},returned:!1,calendarReturned:!1,events:[],facebookLoading:!0}},created:function(){var a=this;i["a"].post("/branch/get",{branchName:this.branchName}).then((function(t){a.returned=!0,201===t.status?(null==t.data&&o["a"].push("/error/404"),a.branch=t.data,a.backGround="background-image: url('"+a.branch.backgroundImg+"');"):o["a"].push("/error/404")})).catch((function(t){a.$bvToast.toast(t+"",r["default"].toastObject("Error")),o["a"].push("/error/404")})),c["default"].loadScript("https://connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v8.0&appId=273465613781500&autoLogAppEvents=1").then((function(){window.FB.XFBML.parse()})),i["a"].post("/event/get/branch",{branchName:this.branchName}).then((function(t){t.data.forEach((function(t){a.events.push({title:t.eventName,start:t.startDate,end:t.endDate,description:t.eventDescription})})),a.calendarReturned=!0})).catch((function(t){a.calendarReturned=!0,404!=t.response.status&&a.$bvToast.toast("Kalender kon niet worden opgehaald",r["default"].toastObject("Error"))}))}},h=u,f=(e("b3d1"),e("2877")),m=Object(f["a"])(h,n,s,!1,null,null,null);t["default"]=m.exports}}]);