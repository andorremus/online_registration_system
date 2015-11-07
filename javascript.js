/***********************************************

* JavaScript Image Clock- by JavaScript Kit (www.javascriptkit.com)
* This notice must stay intact for usage
* Visit JavaScript Kit at http://www.javascriptkit.com/ for this script and 100s more

***********************************************/

var imageclock=new Object()  
  
  // Here the images array were created and after that preloaded to shorten their loading time

  imageclock.digits=["res/common/c0.gif", "res/common/c1.gif", "res/common/c2.gif", "res/common/c3.gif", "res/common/c4.gif", "res/common/c5.gif", "res/common/c6.gif",
      "res/common/c7.gif", "res/common/c8.gif", "res/common/c9.gif", "res/common/cam.gif", "res/common/cpm.gif", "res/common/colon.gif"];
  imageclock.days=["res/common/sunday.gif","res/common/monday.gif","res/common/tuesday.gif","res/common/wednesday.gif","res/common/thursday.gif","res/common/friday.gif","res/common/saturday.gif"];
  imageclock.months=["res/common/January.jpg","res/common/February.jpg","res/common/March.jpg","res/common/April.jpg","res/common/May.jpg","res/common/June.jpg","res/common/July.jpg"
      ,"res/common/August.jpg","res/common/September.jpg","res/common/October.jpg","res/common/November.jpg","res/common/December.jpg"];

  imageclock.instances=0;
  var preloadimages=[];
  var preloadDaysImages=[];
  var preloadMonthsImages=[];

  for (var i=0; i<imageclock.digits.length; i++){ // preload digits images
    preloadimages[i]=new Image();
    preloadimages[i].src=imageclock.digits[i]
  }

  for (var i = 0; i < imageclock.days.length; i++) { // preload days images 
    preloadDaysImages[i]=new Image();
    preloadDaysImages[i].src=imageclock.days[i]
  }

  for (var i = 0; i < imageclock.months.length; i++) { // preload months images
    preloadMonthsImages[i]=new Image()
    preloadMonthsImages[i].src=imageclock.months[i]
  }



  imageclock.imageHTML=function(timestring){ //return timestring (ie: 1:56:38) into string of images instead
    var sections=timestring.split(":")
    if (sections[0]=="0") //If hour field is 0 (aka 12 AM)
      sections[0]="12"
    
    for (var i=0; i<sections.length; i++){
      if (sections[i].length==1)
        sections[i]='<img src="'+imageclock.digits[0]+'" />'+'<img src="'+imageclock.digits[parseInt(sections[i])]+'" />'
      else
        sections[i]='<img src="'+imageclock.digits[parseInt(sections[i].charAt(0))]+'" />'+'<img src="'+imageclock.digits[parseInt(sections[i].charAt(1))]+'" />'
    }
    return sections[0]+'<img src="'+imageclock.digits[12]+'" />'+sections[1]+'<img src="'+imageclock.digits[12]+'" />'+sections[2]
  }

  imageclock.display=function(){
    
    var clockinstance=this
    this.spanid="clockspan"+(imageclock.instances++)
    

    document.write('<span id="'+this.spanid+'"></span>')
    this.update()
    setInterval(function(){clockinstance.update()}, 1000)
    
    
  }

  imageclock.display.prototype.update=function(){
    var dateobj=new Date()
    var currenttime=dateobj.getHours()+":"+dateobj.getMinutes()+":"+dateobj.getSeconds() //create time string
    var currenttimeHTML=imageclock.imageHTML(currenttime)+" "+'<img src="'+((dateobj.getHours()>=12)? imageclock.digits[11] : imageclock.digits[10])+'" />'
    document.getElementById(this.spanid).innerHTML=currenttimeHTML

  }

  // This code here displays the date using the following functions

  imageclock.displayDate= function()
  {
    var dateobj=new Date()
    var todayDay = dateobj.getDay()
    var todayDate = dateobj.getDate().toString()
    var todayMonth = dateobj.getMonth()
    
    document.write(" ")
    document.write('<img src="'+imageclock.days[todayDay]+'"/>')
    
    if(todayDate.length == 1)  // The following 2 blocks of code display the date
    {
      document.write('<img src="'+imageclock.digits[todayDate]+'"/>')
      if(todayDate == 1)
        document.write('<img src="res/common/st.jpg"/>')
      else if(todayDate == 2)
        document.write('<img src="res/common/nd.jpg"/>')
      else if(todayDate == 3)
        document.write('<img src="res/common/rd.jpg"/>')
      else
        document.write('<img src="res/common/th.jpg"/>')
    }

    else
    {
      document.write('<img src="'+imageclock.digits[todayDate.charAt(0)]+'"/>'+'<img src="'+imageclock.digits[todayDate.charAt(1)]+'" />')
      if(todayDate.charAt(0) != 1 && todayDate.charAt(1) == 1)
        document.write('<img src="res/common/st.jpg"/>')
      else if(todayDate.charAt(0) != 1 && todayDate.charAt(1) == 2)
        document.write('<img src="res/common/nd.jpg"/>')
      else if(todayDate.charAt(0) != 1 && todayDate.charAt(1) == 3)
        document.write('<img src="res/common/rd.jpg"/>')
      else
        document.write('<img src="res/common/th.jpg"/>')
    }

    document.write('<img src="res/common/of.jpg"/>')
    document.write('<img src="'+imageclock.months[todayMonth]+'"/>')


    
  }


      
      

      



