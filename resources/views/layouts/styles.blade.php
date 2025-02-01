@foreach ($tema as $key => $theme)
    <style>
        .bg-prt {
            background-color: #000000;
            background-image: linear-gradient(100deg, {{ $theme->nav }} 1%, {{ $theme->side }} 121%);
            background-size: cover;
        }
        .bg-gradient-primary {
            background-color: #000000;
            background-image: linear-gradient(180deg, {{ $theme->nav }} 1%, {{ $theme->side }} 100%);
            background-size: cover;
        }
        #wrapper #content-wrapper {
            background-color: {{ $theme->body }};
            width: 100%;
            overflow-x: hidden;
        }
        .deneb_cta .cta_wrapper {
            padding: 8px;
            max-width: 97%;
            border-radius: 15px;
            margin: auto;
            margin-bottom: -4%;
            color: #fff;
            position: relative;
            background-image: -moz-linear-gradient(0deg, {{ $theme->side }} 0%, {{ $theme->nav }}  100%);
            background-image: -webkit-linear-gradient(0deg, {{ $theme->side }}  0%, {{ $theme->nav }} 100%);
            background-image: -ms-linear-gradient(0deg, {{ $theme->side }} 0%, {{ $theme->nav }}  100%);
            box-shadow: 2.5px 4.33px 15px 0px {{ $theme->nav }};
            z-index: 1;
        }
        ::-webkit-scrollbar {
            display: none;
        }

        .float-nav {
          position: fixed;
          bottom: 20px;
          right: 20px;
          z-index: 9999999;
        }
        .float-nav:hover {
        cursor:pointer;
        }
        .float-nav-2 {
          position: fixed;
          bottom: 20px;
          right: 20px;
          z-index: 9999;
        }
        .float-nav-2:hover {
        cursor:pointer;
        }
        .main-nav-2 {
          font-family: sans-serif;
          position: absolute;
          bottom: 7px;
          right: 0px;
          width: 420px;
          margin-bottom:40px;
        }
        .main-nav-2.active {
          display: block;
          opacity: 1;
        }
        .main-nav-2 > ul {
          width: 100%;
          display: block;
          list-style: none;
            z-index:10;
        }
        .main-nav-2 > ul > li {
            display: flex;
            justify-content: space-between;
        }
        .main-nav-2 > ul > li > a {
          text-decoration: none;
          display: block;
          font-weight: 200;
          padding: 18px 80px 18px 18px;
          color: black;
        }
        .main-nav-2 > ul > li > a:hover {
          font-weight: 400;
        }
    </style>
@endforeach
