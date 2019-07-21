'use strict';

let width = 320;
let height = 240;

let video = null;
let canvas = null;
let video_div = null;
let put_file_div = null;
let snapshotButton = null;
let overlay = null;
let backgroundSelect = null;
let select_filter = null;
let overlay_btn = null;

function start()
{
    snapshotButton = document.querySelector('button#shot');
    video = window.video = document.querySelector('#video_launch');
    canvas = window.canvas = document.querySelector('#canvas');
    put_file_div = document.querySelector('.put_file');
    video_div = document.querySelector('.video');
    backgroundSelect = document.querySelector('div#background');
    overlay = document.querySelector('img#overlay_img');
    select_filter = document.querySelector("#select_filter");

    canvas.width = width;
    canvas.height = height;
    snapshotButton.disabled = true;
    snapshotButton.className = 'btn disabled';

    // https://developer.mozilla.org/es/docs/Web/API/MediaDevices/getUserMedia
    var promisifiedOldGUM = function(constraints) {

        var getUserMedia = (navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia ||
            navigator.oGetUserMedia);

        if(!getUserMedia) {
            return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
        }

        return new Promise(function(resolve, reject) {
            getUserMedia.call(navigator, constraints, resolve, reject);
        });
    }

    if(navigator.mediaDevices === undefined) {
        console.log("getUserMedia() not supported.");
        navigator.mediaDevices = {};
    }

    if(navigator.mediaDevices.getUserMedia === undefined) {
        navigator.mediaDevices.getUserMedia = promisifiedOldGUM;
    }

    var constraints = { audio: false, video: { width: { min: 320 }, height: { min: 240 }  }};

    navigator.mediaDevices.getUserMedia(constraints)
        .then(
            function (stream) {
                if (navigator.mozGetUserMedia) {
                    video.mozSrcObject = video.src = stream;
                } else {
                    let vendorURL = window.URL || window.webkitURL;
                    video.srcObject = video.src = stream;
                }
            })
        .catch(
            function (error) {
                console.log(error);
                console.log("An error occured : " + error.message);
            }
        );

    backgroundSelect.addEventListener('click', function(e) {
        e.preventDefault();
        let nodes = this.childNodes;
        let filter_img = document.querySelector("#filter_img");
        for (let i = 0; i < nodes.length; i++)
        {
            nodes[i].className = "";
            select_filter.src = "";
            select_filter.style.display = "none";
            filter_img.value = "";
        }
        if (e.target.localName === 'img')
        {
            e.target.className = "selected";
            if (video_div.className.search("hide") == -1)
            {
                select_filter.style.display = "block";
                if (select_filter.src.search("/pages\/index.php/i")) {
                    select_filter.src = select_filter.src.replace("pages/index.php", "layout/img/");
                }
                if (select_filter.src.search("/pages/i")) {
                    select_filter.src = select_filter.src.replace("pages/", "layout/img/");
                }
                select_filter.src += e.target.id + ".png";
                filter_img.value = e.target.id + ".png";
            }
            snapshotButton.disabled = false;
            snapshotButton.className = 'btn';
        }
    });

    if (snapshotButton)
    {
        snapshotButton.addEventListener('click', function (e) {
            create_photo();
            e.preventDefault();
        }, false);
    }
}

function create_photo()
{
	let context = canvas.getContext('2d');

	if (width && height)
	{
        context.translate(width, 0);
        context.scale(-1, 1);
		context.width = width;
		context.height = height;
		context.drawImage(video, 0, 0, context.width, context.height);
		let data = canvas.toDataURL('image/png');
		document.querySelector("#img_background").value = data;
		document.querySelector("#filter_form").submit();
	}

}

window.addEventListener('load', start, false);