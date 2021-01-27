<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <!-- Standard/Facebook -->
    <meta property="og:url" content="https://wallofhistory.com/" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Wall of History" />
    <meta property="og:description" content="The complete BIONICLE legend, now on the web!" />
    <meta property="og:image" content="https://wallofhistory.com/img/ogp.png" />
    <meta property="og:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Wall of History" />
    <meta name="twitter:site" content="@Wall_of_History" />
    <meta name="twitter:creator" content="@JSLBrowning" />
	<meta name="twitter:description" content="The complete BIONICLE legend, now on the web!" />
	<meta name="twitter:image" content="https://wallofhistory.com/img/ogp%20(Twitter).png" />
    <meta name="twitter:image:alt" content="Wall of History: The Ultimate BIONICLE Experience" />
    <!-- end of OGP data -->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <title>Wall of History</title>
</head>
<body>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v8.0" nonce="sckJu8Ly"></script>
    <header>
        <img src="/img/Faber-Files-Bionicle-logo-Transparent.png" alt="BIONICLE" height="80" width="405" style="cursor: pointer;" onclick="window.location.href='/'">
        <p><a style="cursor: pointer;" onclick="jumpTo()">Read</a> | <a href="/read/">Contents</a> | <a href="/reference/">Reference</a> | <a href="/search/">Search</a> | <a href="/about/">About</a> | <a href="https://blog.wallofhistory.com/">Blog</a> | <a href="/contact/">Contact</a></p>
    </header>
    <main>
        <video style="margin-top: -0.75em;" controls autoplay>
            <source src="https://wallofhistory.com/img/Wall%20of%20History%20Ad.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <p style="text-align: center;">Welcome to <strong>Wall of History</strong>, a web archive of the entire BIONICLE legend, compiled into an accessible, easy-to-read format.</p>
        <p style="text-align: center;">You can start or continue reading by hitting the button below, or you can customize your reading experience <a href="/settings/">here</a>.</p>
        <nav>
            <button onclick="jumpTo()">Read!</button>
        </nav>
        <p style="margin-top: 1em; text-align: center;">Find Us</p>
        <div class="social" style="display: flex; flex-wrap: wrap; justify-content: space-around;">
            <a href="https://discord.com/invite/V7KUyye" width="32px" height="32px">
                <img src="../img/index/Discord-Logo-White.png" width="32px" height="32px">
            </a>
            <a href="https://www.facebook.com/WallofHistory" width="32px" height="32px">
                <img src="../img/index/f_logo_RGB-White_1024.png" width="32px" height="32px">
            </a>
            <a href="https://www.instagram.com/Wall_of_History/" width="32px" height="32px">
                <img src="../img/index/white-instagram-logo-transparent-background.png" width="32px" height="32px">
            </a>
            <a href="https://www.reddit.com/r/WallofHistory/" width="32px" height="32px">
                <img src="../img/index/reddit_share_silhouette_128.png" width="32px" height="32px">
            </a>
            <a href="https://twitter.com/Wall_of_History" width="32px" height="32px">
                <img src="../img/index/Twitter_Social_Icon_Circle_White.png" width="32px" height="32px">
            </a>
            <a href="https://www.youtube.com/channel/UCNnu_YlSMehzaAZwWS6gkVg" width="32px" height="32px">
                <img src="../img/index/yt_logo_mono_dark.png" width="32px" height="32px">
            </a>
        </div>
        <p style="text-align: center;">Share</p>
        <div class="social" style="display: flex; flex-wrap: wrap; justify-content: space-around;">
            <a href="https://www.facebook.com/sharer/?u=https%3A%2F%2Fwallofhistory.com%2F" width="32px" height="32px">
                <img src="../img/index/f_logo_RGB-White_1024.png" width="32px" height="32px">
            </a>
            <a href="https://twitter.com/intent/tweet?text=I'm living the %23BIONICLE legend on @Wall_of_History, and you should be too! https://wallofhistory.com/" width="32px" height="32px">
                <img src="../img/index/Twitter_Social_Icon_Circle_White.png" width="32px" height="32px">
            </a>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
</body>
</html>