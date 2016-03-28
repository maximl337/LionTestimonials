@extends('layouts.app')

@section('head')

<style type="text/css">
    .phone, .desktop, .done {
        display: none;
    }
    .safe-hide {
        height: 0px;
        width: 0px;
        border: 0px;
        background: none;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @include('testimonials._partials.create-from-phone')

            @include('testimonials._partials.create-from-desktop')

            <div class="done alert alert-success">
                <p>Thankyou for submitting your testimonial</p>
            </div>
            
        </div>
    </div>
</div>
@endsection


@section('footer')

@if(Session::has('error'))
<script type="text/javascript">
    
    swal("Uh oh!", "{{ Session::get('error') }}", "error");


</script>
@endif

@if(Session::has('success'))
<script type="text/javascript">
    
    swal("Good job!", "{{ Session::get('success') }}", "success");


</script>
@endif

@if(env('APP_ENV') == 'staging' || env('APP_ENV') == 'production') 

<script type="text/javascript">

    // hide video capibility if no SSL
    if(location.protocol != 'https:') {
        $("#desktop-video").remove();
        $("#submit-without-video").html("Submit");
    }
    
</script>
    
@endif

<script type="text/javascript">

    var isMobile = false; //initiate as false
    // device detection
    if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) isMobile = true;

    
    if(isMobile) {

        $(".phone").show();
        $(".desktop").remove();

    } else {

        $(".desktop").show();
        $(".phone").remove();
    }

    (function() {
        var params = {},
            r = /([^&=]+)=?([^&]*)/g;

        function d(s) {
            return decodeURIComponent(s.replace(/\+/g, ' '));
        }

        var match, search = window.location.search;
        while (match = r.exec(search.substring(1))) {
            params[d(match[1])] = d(match[2]);

            if(d(match[2]) === 'true' || d(match[2]) === 'false') {
                params[d(match[1])] = d(match[2]) === 'true' ? true : false;
            }
        }

        window.params = params;
    })();
</script>


<script>

    function intallFirefoxScreenCapturingExtension() {
        InstallTrigger.install({
            'Foo': {
                // URL: 'https://addons.mozilla.org/en-US/firefox/addon/enable-screen-capturing/',
                URL: 'https://addons.mozilla.org/firefox/downloads/file/355418/enable_screen_capturing_in_firefox-1.0.006-fx.xpi?src=cb-dl-hotness',
                toString: function() {
                    return this.URL;
                }
            }
        });
    }

    var recordingDIV = document.querySelector('.recordrtc');
    var recordingMedia = recordingDIV.querySelector('.recording-media');
    var recordingPlayer = recordingDIV.querySelector('video');
    var mediaContainerFormat = recordingDIV.querySelector('.media-container-format');
    
    window.onbeforeunload = function() {
        recordingDIV.querySelector('button').disabled = false;
        recordingMedia.disabled = false;
        mediaContainerFormat.disabled = false;
    };
    
    recordingDIV.querySelector('button').onclick = function() {
        
        var button = this;

        if(button.innerHTML === 'Stop Recording') {
            button.disabled = true;
            button.disableStateWaiting = true;
            setTimeout(function() {
                button.disabled = false;
                button.disableStateWaiting = false;
            }, 2 * 1000);
            
            button.innerHTML = 'Start Recording';

            function stopStream() {
                if(button.stream && button.stream.stop) {
                    document.getElementById("submit-without-video").style.display = 'none';
                    button.stream.stop();
                    button.stream = null;
                }
            }
            
            if(button.recordRTC) {
                if(button.recordRTC.length) {
                    button.recordRTC[0].stopRecording(function(url) {
                        if(!button.recordRTC[1]) {
                            button.recordingEndedCallback(url);
                            stopStream();

                            saveToDiskOrOpenNewTab(button.recordRTC[0]);
                            return;
                        }

                        button.recordRTC[1].stopRecording(function(url) {
                            button.recordingEndedCallback(url);
                            stopStream();
                        });
                    });
                }
                else {
                    button.recordRTC.stopRecording(function(url) {
                        button.recordingEndedCallback(url);
                        stopStream();

                        saveToDiskOrOpenNewTab(button.recordRTC);
                    });
                }
            }
            
            return;
        }
        
        button.disabled = true;
        
        var commonConfig = {
            onMediaCaptured: function(stream) {
                button.stream = stream;
                if(button.mediaCapturedCallback) {
                    button.mediaCapturedCallback();
                }

                button.innerHTML = 'Stop Recording';
                button.disabled = false;
            },
            onMediaStopped: function() {
                button.innerHTML = 'Start Recording';
                
                if(!button.disableStateWaiting) {
                    button.disabled = false;
                }
            },
            onMediaCapturingFailed: function(error) {
                if(error.name === 'PermissionDeniedError' && !!navigator.mozGetUserMedia) {
                    intallFirefoxScreenCapturingExtension();
                }
                
                commonConfig.onMediaStopped();
            }
        };

        var mimeType = 'video/webm';
        if(mediaContainerFormat.value === 'Mp4') {
            mimeType = 'video/mp4';
        }

        if(recordingMedia.value === 'record-audio-plus-video') {
            captureAudioPlusVideo(commonConfig);
            
            button.mediaCapturedCallback = function() {

                if(typeof MediaRecorder === 'undefined') { // opera or chrome etc.
                    button.recordRTC = [];

                    if(!params.bufferSize) {
                        // it fixes audio issues whilst recording 720p
                        params.bufferSize = 16384;
                    }

                    var options = {
                        type: 'audio',
                        bufferSize: typeof params.bufferSize == 'undefined' ? 0 : parseInt(params.bufferSize),
                        sampleRate: typeof params.sampleRate == 'undefined' ? 44100 : parseInt(params.sampleRate),
                        leftChannel: params.leftChannel || false,
                        disableLogs: params.disableLogs || false,
                        recorderType: webrtcDetectedBrowser === 'edge' ? StereoAudioRecorder : null
                    };

                    if(typeof params.sampleRate == 'undefined') {
                        delete options.sampleRate;
                    }

                    var audioRecorder = RecordRTC(button.stream, options);

                    var videoRecorder = RecordRTC(button.stream, {
                        type: 'video',
                        disableLogs: params.disableLogs || false,
                        canvas: {
                            width: params.canvas_width || 320,
                            height: params.canvas_height || 240
                        },
                        frameInterval: typeof params.frameInterval !== 'undefined' ? parseInt(params.frameInterval) : 20 // minimum time between pushing frames to Whammy (in milliseconds)
                    });

                    // to sync audio/video playbacks in browser!
                    videoRecorder.initRecorder(function() {
                        audioRecorder.initRecorder(function() {
                            audioRecorder.startRecording();
                            videoRecorder.startRecording();
                        });
                    });

                    button.recordRTC.push(audioRecorder, videoRecorder);

                    button.recordingEndedCallback = function() {
                        var audio = new Audio();
                        audio.src = audioRecorder.toURL();
                        audio.controls = true;
                        audio.autoplay = true;

                        audio.onloadedmetadata = function() {
                            recordingPlayer.src = videoRecorder.toURL();
                            recordingPlayer.play();
                        };

                        recordingPlayer.parentNode.appendChild(document.createElement('hr'));
                        recordingPlayer.parentNode.appendChild(audio);

                        if(audio.paused) audio.play();
                    };
                    return;
                }

                button.recordRTC = RecordRTC(button.stream, {
                    type: 'video',
                    mimeType: mimeType,
                    disableLogs: params.disableLogs || false,
                    // bitsPerSecond: 25 * 8 * 1025 // 25 kbits/s
                    getNativeBlob: false // enable it for longer recordings
                });
                
                button.recordingEndedCallback = function(url) {
                    recordingPlayer.muted = false;
                    recordingPlayer.removeAttribute('muted');
                    recordingPlayer.src = url;
                    recordingPlayer.play();

                    recordingPlayer.onended = function() {
                        recordingPlayer.pause();
                        recordingPlayer.src = URL.createObjectURL(button.recordRTC.blob);
                    };
                };
                
                button.recordRTC.startRecording();
            };
        }
        
    };
    
    function captureVideo(config) {
        captureUserMedia({video: true}, function(videoStream) {
            recordingPlayer.srcObject = videoStream;
            recordingPlayer.play();
            
            config.onMediaCaptured(videoStream);
            
            videoStream.onended = function() {
                config.onMediaStopped();
            };
        }, function(error) {
            config.onMediaCapturingFailed(error);
        });
    }
    
    function captureAudio(config) {
        captureUserMedia({audio: true}, function(audioStream) {
            recordingPlayer.srcObject = audioStream;
            recordingPlayer.play();
            
            config.onMediaCaptured(audioStream);
            
            audioStream.onended = function() {
                config.onMediaStopped();
            };
        }, function(error) {
            config.onMediaCapturingFailed(error);
        });
    }

    function captureAudioPlusVideo(config) {
        captureUserMedia({video: true, audio: true}, function(audioVideoStream) {
            recordingPlayer.srcObject = audioVideoStream;
            recordingPlayer.play();
            
            config.onMediaCaptured(audioVideoStream);
            
            audioVideoStream.onended = function() {
                config.onMediaStopped();
            };
        }, function(error) {
            config.onMediaCapturingFailed(error);
        });
    }
    
    function captureScreen(config) {
        getScreenId(function(error, sourceId, screenConstraints) {
            if (error === 'not-installed') {
                document.write('<h1><a target="_blank" href="https://chrome.google.com/webstore/detail/screen-capturing/ajhifddimkapgcifgcodmmfdlknahffk">Please install this chrome extension then reload the page.</a></h1>');
            }

            if (error === 'permission-denied') {
                alert('Screen capturing permission is denied.');
            }

            if (error === 'installed-disabled') {
                alert('Please enable chrome screen capturing extension.');
            }
            
            if(error) {
                config.onMediaCapturingFailed(error);
                return;
            }

            delete screenConstraints.video.mozMediaSource;
            captureUserMedia(screenConstraints, function(screenStream) {
                recordingPlayer.srcObject = screenStream;
                recordingPlayer.play();
                
                config.onMediaCaptured(screenStream);
                
                screenStream.onended = function() {
                    config.onMediaStopped();
                };
            }, function(error) {
                config.onMediaCapturingFailed(error);
            });
        });
    }

    function captureAudioPlusScreen(config) {
        getScreenId(function(error, sourceId, screenConstraints) {
            if (error === 'not-installed') {
                document.write('<h1><a target="_blank" href="https://chrome.google.com/webstore/detail/screen-capturing/ajhifddimkapgcifgcodmmfdlknahffk">Please install this chrome extension then reload the page.</a></h1>');
            }

            if (error === 'permission-denied') {
                alert('Screen capturing permission is denied.');
            }

            if (error === 'installed-disabled') {
                alert('Please enable chrome screen capturing extension.');
            }
            
            if(error) {
                config.onMediaCapturingFailed(error);
                return;
            }

            screenConstraints.audio = true;

            delete screenConstraints.video.mozMediaSource;
            captureUserMedia(screenConstraints, function(screenStream) {
                recordingPlayer.srcObject = screenStream;
                recordingPlayer.play();
                
                config.onMediaCaptured(screenStream);
                
                screenStream.onended = function() {
                    config.onMediaStopped();
                };
            }, function(error) {
                config.onMediaCapturingFailed(error);
            });
        });
    }
    
    function captureUserMedia(mediaConstraints, successCallback, errorCallback) {
        navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
    }
    
    function setMediaContainerFormat(arrayOfOptionsSupported) {
        var options = Array.prototype.slice.call(
            mediaContainerFormat.querySelectorAll('option')
        );
        
        var selectedItem;
        options.forEach(function(option) {
            option.disabled = true;
            
            if(arrayOfOptionsSupported.indexOf(option.value) !== -1) {
                option.disabled = false;
                
                if(!selectedItem) {
                    option.selected = true;
                    selectedItem = option;
                }
            }
        });
    }
    
    recordingMedia.onchange = function() {
        var options = [];
        if(webrtcDetectedBrowser === 'firefox') {
            if(this.value === 'record-audio') {
                options.push('Ogg');
            }
            else {
                options.push('WebM', 'Mp4');
            }

            setMediaContainerFormat(options);
            return;
        }
        if(this.value === 'record-audio') {
            setMediaContainerFormat(['WAV', 'Ogg']);
            return;
        }
        setMediaContainerFormat(['WebM', 'Mp4', 'Ogg']);
    };

    if(webrtcDetectedBrowser === 'edge') {
        // webp isn't supported in Microsoft Edge
        // neither MediaRecorder API
        // so lets disable both video/screen recording options

        console.warn('Neither MediaRecorder API nor webp is supported in Microsoft Edge. You cam merely record audio.');

        recordingMedia.innerHTML = '<option value="record-audio">Audio</option>';
        setMediaContainerFormat(['WAV']);
    }

    if(webrtcDetectedBrowser === 'firefox') {
        // Firefox implemented both MediaRecorder API as well as WebAudio API
        // Their MediaRecorder implementation supports both audio/video recording in single container format
        // Remember, we can't currently pass bit-rates or frame-rates values over MediaRecorder API (their implementation lakes these features)

        $("#desktop-video").show();

        recordingMedia.innerHTML = '<option value="record-audio-plus-video" selected="selected">Audio+Video</option>' 
                                    + recordingMedia.innerHTML;

        setMediaContainerFormat(['WebM', 'Mp4']);
    }

    if(webrtcDetectedBrowser === 'chrome') {

        $("#desktop-video").show();
        recordingMedia.innerHTML = '<option value="record-audio-plus-video" selected="selected">Audio+Video</option>' 
                                    + recordingMedia.innerHTML;

        if(typeof MediaRecorder === 'undefined') {
            console.info('This RecordRTC demo merely tries to playback recorded audio/video sync inside the browser. It still generates two separate files (WAV/WebM).');
        }
    }
    
    function saveToDiskOrOpenNewTab(recordRTC) {
        recordingDIV.querySelector('#save-to-disk').parentNode.style.display = 'block';
        recordingDIV.querySelector('#save-to-disk').onclick = function() {
            if(!recordRTC) return alert('No recording found.');
            
            recordRTC.save();
        };
        
        recordingDIV.querySelector('#open-new-tab').onclick = function() {
            if(!recordRTC) return alert('No recording found.');
            
            window.open(recordRTC.toURL());
        };

        recordingDIV.querySelector('#upload-to-server').disabled = false;
        recordingDIV.querySelector('#upload-to-server').onclick = function() {
            if(!recordRTC) return alert('No recording found.');
            this.disabled = true;

            var button = this;
            uploadToServer(recordRTC);
        };

    }

    function uploadToServer(recordRTC) {

        var validate = validateInput();

        if(!validate) {

            swal("Uh oh!", "Email or rating not given", "error");

            recordingDIV.querySelector('#upload-to-server').disabled = false;

            return false;
        }

        var blob = recordRTC instanceof Blob ? recordRTC : recordRTC.blob;

        var fileType = blob.type.split('/')[0] || 'audio';

        var fileName = (Math.random() * 1000).toString().replace('.', '');

        if (fileType === 'audio') {
            fileName += '.' + (!!navigator.mozGetUserMedia ? 'ogg' : 'wav');
        } else {
            fileName += '.webm';
        }

        // create FormData
        var formData = new FormData();

        formData.append('video', blob);

        formData.append('_token', "{{ csrf_token() }}");

        formData.append('contact_id', "{{ $data['contact']->id }}");

        formData.append('user_id', "{{ $data['user']->id }}");

        formData.append('email', $(".desktop #email").val());

        formData.append('rating', $(".desktop select#rating").val());

        formData.append('body', $(".desktop textarea#body").val());

        $.ajax({
            url: "{{ env('APP_URL') }}testimonials/desktop",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                swal("Good job!", "Testimonials added", "success");
                $(".desktop").hide();
                $(".done").show();
            },
            error: function(jqXHR, textStatus, errorMessage) {
                //alert('Error:' + JSON.stringify(errorMessage));

                swal("Error!", jqXHR.responseText, "error");
            }
        });
    }

    function validateInput() {
        
        var message;

        var email = $(".desktop #email").val();

        var rating = $(".desktop select#rating").val();

        if(email.length <= 0 || rating === null) {

            return false;
        }

        return true;
    }

    $(document).on("click", "#submit-without-video", function(e) {

        e.preventDefault();

        $(this).attr("disabled", "disabled");

        var formData = new FormData();

        formData.append('_token', "{{ csrf_token() }}");

        formData.append('contact_id', "{{ $data['contact']->id }}");

        formData.append('user_id', "{{ $data['user']->id }}");

        formData.append('email', $(".desktop #email").val());

        formData.append('rating', $(".desktop select#rating").val());

        formData.append('body', $(".desktop textarea#body").val());

        $.ajax({
            url: "{{ env('APP_URL') }}testimonials/desktop",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                swal("Good job!", "Testimonials added", "success");
                $(".desktop").hide();
                $(".done").show();
            },
            error: function(jqXHR, textStatus, errorMessage) {
                //alert('Error:' + JSON.stringify(errorMessage));

                swal("Error!", jqXHR.responseText, "error");
            }
        });
    });
</script>

@endsection