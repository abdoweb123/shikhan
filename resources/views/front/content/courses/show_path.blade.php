<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tangerine|Cairo|open+sans|Almarai|Tajawal">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>courses</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="fontawesome/css/all.min.css" rel="stylesheet">
    <link href="css/style-courses.css" rel="stylesheet">
    <link href="sj/global.css" rel="stylesheet">

    <style>
        body{
            direction: rtl;
            display: grid;
            /*justify-content: center;*/
            gap: 10px;
            align-items: center;
            width: 100vw;
            min-height: 100vh;
            font-family: "Cairo", "Almarai", sans-serif;
            overflow-x: hidden;
        }
        .course_page{
            margin-block: 20px;
            margin-inline: 20px;
            width: auto;
        }
        .course_page > .row{
            justify-content: space-between;
            align-items: center;
            row-gap: 20px;
        }

        .side-nav{
            border-radius: 7px;
            box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.15);
            margin-inline: auto;
            position: relative;
        }

        .divScroll{
            background-color: #d1edff;
            overflow-y: auto;
            direction: ltr;
            height: 500px;
            border-block: 2px solid #000000;
            scroll-behavior: smooth;
        }

        .side-nav .head p{
            text-align: center;
            font-size: 48px;
            background-color: #109b00;
            border-radius: 5px;
            color: #fff;
            margin-top: 7px;
        }

        .side-nav > .row{
            row-gap: 10px;
        }

        .side-nav .card-body{
            padding-block: 20px 40px;
            background-color: #d1edff;
        }

        .holder{
            /*height: 600px;*/
            display: grid;
            gap: 10px;
            /*padding-block: 10px;*/
        }

        .holder > *{
            background-color: dodgerblue;
            min-height: 50px;
            width: 100%;
            font-size: 20px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 3px;
            text-align: center;
            padding-block: 5px;
        }

        .holder p{
            min-height: 50px;
            display: grid;
            grid-template-columns: auto 4fr;
        }

        .holder div{
            min-height: 80px;
            background-color: #ffdbdb;
        }
        .holder i{
            margin:3px;
        }

        .holder i:before{
            width: 40px;
            height: 40px;
            background-color: #ffffff;
            border-radius: 5px;
            font-size: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #07a811;
        }
        .holder i.exam:before{
            color: #b22020;
        }
        .holder a{
            color: #fff !important;
        }

        .side-nav .icon{
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 40px;
            width: 50px;
            height: 50px;
            color: #ffffff;
            text-shadow: 0 0 2px #000000;
            background-color: #000000d4;
            border-radius: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            cursor: pointer;

        }

        .side-nav .icon.up{
            transform: translate(-50%, -50%);
        }

        .exhibit {
            width: 100%;
            min-height: 300px;
            /*background-color: #e1e1e1;*/
            justify-content: center;
            align-items: center;
            border-radius: 7px;
            display: grid;
            grid-template-columns: 100%;
            padding: 7px;
            gap: 10px;
            box-shadow: 0 0 10px 1px #ddd;
        }

        .exhibit p{
            /*height: 100px;*/
            background-color: #069f06;
            color: white;
            border-radius: 4px;
            text-align: center;
        }

        .exhibit iframe{
            height: 600px;
            /*background-color: #690000;*/
            /*border: 1px solid #690000;*/
            width: 100%;
            border-radius: 5px;
        }
        .exhibit img{
            height: auto;
            width: 100%;
            border-radius: 5px;
        }





    </style>

</head>

<body id="top">

<div class="container-fluid course_page">
    <div class="row">
        <div class="side-nav col col-11 col-md-4">
            <div class="row">

                <div class="col-12">
                    <div class="head">
                        <p>عنوان</p>
                    </div>
                </div>
                <div class="col-12 card-body">
                    <i class="icon up fa-solid fa-angle-up" id="iconUp_coursePage_scroll" data-link-class="divScroll"></i>
                    <div class="divScroll">
                        <div class="holder">
                            <p><span></span><a>مقدمة المؤلف</a></p>
                            <p>
                <span>
                    <i class="fa-solid fa-file-pdf"></i>
                    <i class="fa-solid fa-video"></i>
                </span>
                                <a href>لمّا كان الاعتراف بالكرامة المتأصلة في جميع لمّا كان الاعتراف بالكرامة المتأصلة في جميع</a>
                            </p>


                            <p>
                  <span>
                      <i class="fa-solid fa-file-lines exam"></i>
                  </span>
                                <a href="">لمّا كان الاعتراف بالكرامة المتأصلة في جميع </a>
                            </p>

                            <p>
                                <span>
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <i class="fa-solid fa-video"></i>
                                </span>
                                <a href>لمّا كان الاعتراف بالكرامة المتأصلة في جميع لمّا كان الاعتراف بالكرامة المتأصلة في جميع</a>
                            </p>

                            <p>
                                <span>
                                    <i class="fa-solid fa-file-lines exam"></i>
                                </span>
                                <a href="">لمّا كان الاعتراف بالكرامة المتأصلة في جميع </a>
                            </p>
                            <p>
                                <span>
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <i class="fa-solid fa-video"></i>
                                </span>
                                <a href>لمّا كان الاعتراف بالكرامة المتأصلة في جميع لمّا كان الاعتراف بالكرامة المتأصلة في جميع</a>
                            </p>

                            <p>
                                <span>
                                    <i class="fa-solid fa-file-lines exam"></i>
                                </span>
                                <a href="">لمّا كان الاعتراف بالكرامة المتأصلة في جميع </a>
                            </p>
                            <p>
                                <span>
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <i class="fa-solid fa-video"></i>
                                </span>
                                <a href>لمّا كان الاعتراف بالكرامة المتأصلة في جميع لمّا كان الاعتراف بالكرامة المتأصلة في جميع</a>
                            </p>

                            <p>
                                <span>
                                    <i class="fa-solid fa-file-lines exam"></i>
                                </span>
                                <a href="">لمّا كان الاعتراف بالكرامة المتأصلة في جميع </a>
                            </p>


                        </div>
                    </div>
                    <i class="icon fa-solid fa-angle-down" id="iconDown_coursePage_scroll"></i>
                </div>

            </div>
        </div>

        <div class="col-12 col col-md-8">
            <div class="exhibit">
                <p class="alert alert-primary"> Lorem ipsum dolor sit amet, consectetur.  </p>
                <!--        <iframe  src="https://www.youtube.com/embed/X2TLdTwYc6s" title="دورة تسويق الأفكار الشيخ د.عبدالله سالم باهمام" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>-->
                <img src="images/pic-2.jpg">
            </div>
        </div>

    </div>
</div>


<script>
    function btnScroll(btn, dir){
        if(!btn){return}
        let con = btn.parentElement.querySelector(".divScroll")
        btn.onclick = ()=>{
            if(dir==="up"){con.scrollTop -= 50}else{con.scrollTop += 50}
        }
    }
    let btnUp = document.getElementById("iconUp_coursePage_scroll")
    let btnDown = document.getElementById("iconDown_coursePage_scroll")
    btnScroll(btnUp, "up")
    btnScroll(btnDown, "down")

    let containers = document.querySelectorAll(".divScroll")
    containers.forEach(con=>{
        con.onscroll = function(){
            let curPos = con.scrollTop
            let areaScroll = con.scrollHeight - con.clientHeight
            if(curPos >= areaScroll - 1){
                // reached to the button, do something
            }
        }
    })
</script>

</body>
</html>
