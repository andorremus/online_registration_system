 if(document.images)
  {
    homeImage= new Image();
    aboutImage= new Image();
    newsImage= new Image();
    reviewsImage=new Image();
    contactImage=new Image();
    menuImage=new Image();
    feedbackImage=new Image();
    quizImage=new Image();

    homeImage.src="../a2/assets/img/home.jpg";
    aboutImage.src="/a2/assets/about.jpg";
    newsImage.src="/a2/assets/news.jpg";
    reviewsImage.src="/a2/assets/reviews.jpg";
    contactImage.src="/a2/assets/contact.jpg";
    menuImage.src="/a2/assets/menu.jpg";
    feedbackImage.src="/a2/assets/feedback.jpg";
    quizImage.src="/a2/assets/quiz.jpg";

  }
  else
  {
    homeImage= "";
    aboutImage= "";
    newsImage= "";
    reviewsImage= "";
    contactImage= "";
    menuImage=  "";
    quizImage= "";
    feedbackImage="";

    document.roll="";
  } 

/***********************************************

* JavaScript Image Clock- by JavaScript Kit (www.javascriptkit.com)
* This notice must stay intact for usage
* Visit JavaScript Kit at http://www.javascriptkit.com/ for this script and 100s more

***********************************************/

var imageclock=new Object();
  
  // Here the images array were created and after that preloaded to shorten their loading time

  imageclock.digits=["c0.gif", "c1.gif", "c2.gif", "c3.gif", "c4.gif", "c5.gif", "c6.gif", "c7.gif", "c8.gif", "c9.gif", "cam.gif", "cpm.gif", "colon.gif"]
  imageclock.days=["Sunday.gif","Monday.gif","Tuesday.gif","Wednesday.gif","Thursday.gif","Friday.gif","Saturday.gif"]
  imageclock.months=["January.jpg","February.jpg","March.jpg","April.jpg","May.jpg","June.jpg","July.jpg","August.jpg","September.jpg","October.jpg","November.jpg","December.jpg"]

  imageclock.instances=0;
  var preloadimages=[];
  var preloadDaysImages=[];
  var preloadMonthsImages=[];

  for (var i=0; i<imageclock.digits.length; i++){ // preload digits images
    preloadimages[i]=new Image();
    preloadimages[i].src=imageclock.digits[i];
  }

  for (var i = 0; i < imageclock.days.length; i++) { // preload days images 
    preloadDaysImages[i]=new Image();
    preloadDaysImages[i].src=imageclock.days[i];
  }

  for (var i = 0; i < imageclock.months.length; i++) { // preload months images
    preloadMonthsImages[i]=new Image()
    preloadMonthsImages[i].src=imageclock.months[i];
  }



  imageclock.imageHTML=function(timestring){ //return timestring (ie: 1:56:38) into string of images instead
    var sections=timestring.split(":")
    if (sections[0]=="0") //If hour field is 0 (aka 12 AM)
      sections[0]="12"
    
    for (var i=0; i<sections.length; i++){
      if (sections[i].length==1)
        sections[i]='<img src="'+"../assets/img/" +imageclock.digits[0]+'" />'+'<img src="'+"../assets/img/" +imageclock.digits[parseInt(sections[i])]+'" />'
      else
        sections[i]='<img src="'+"../assets/img/" +imageclock.digits[parseInt(sections[i].charAt(0))]+'" />'+'<img src="'+"../assets/img/" +imageclock.digits[parseInt(sections[i].charAt(1))]+'" />'
    }
    return sections[0]+'<img src="'+"../assets/img/" +imageclock.digits[12]+'" />'+sections[1]+'<img src="'+"../assets/img/" +imageclock.digits[12]+'" />'+sections[2]
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
    var currenttimeHTML=imageclock.imageHTML(currenttime)+" "+'<img src="'+"../assets/img/" +((dateobj.getHours()>=12)? imageclock.digits[11] : imageclock.digits[10])+'" />'
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
    document.write('<img src="'+"../assets/img/" +imageclock.days[todayDay]+'"/>')
    
    if(todayDate.length == 1)  // The following 2 blocks of code display the date
    {
      document.write('<img src="'+"../assets/img/" +imageclock.digits[todayDate]+'"/>')
      if(todayDate == 1)
        document.write('<img src="../assets/img/st.jpg"/>')
      else if(todayDate == 2)
        document.write('<img src="../assets//img/nd.jpg"/>')
      else if(todayDate == 3)
        document.write('<img src="../assets/img/rd.jpg"/>')
      else
        document.write('<img src="../assets/img/th.jpg"/>')
    }

    else
    {
      document.write('<img src="'+"../assets/img/" +imageclock.digits[todayDate.charAt(0)]+'"/>'+'<img src="'+"../assets/img/" +imageclock.digits[todayDate.charAt(1)]+'" />')
      if(todayDate.charAt(0) != 1 && todayDate.charAt(1) == 1)
        document.write('<img src="../assets/img/st.jpg"/>')
      else if(todayDate.charAt(0) != 1 && todayDate.charAt(1) == 2)
        document.write('<img src="../assets/img/nd.jpg"/>')
      else if(todayDate.charAt(0) != 1 && todayDate.charAt(1) == 3)
        document.write('<img src="../assets/img/rd.jpg"/>')
      else
        document.write('<img src="../assets/img/th.jpg"/>')
    }

    document.write('<img src="../assets/img/of.jpg"/>')
    document.write('<img src="'+"../assets/img/"+imageclock.months[todayMonth]+'"/>')


    
  };


      



