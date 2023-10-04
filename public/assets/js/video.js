  // Get the video player element
                    const videoPlayer = document.getElementById("videoPlayer");

                    // Define the video sources
                    const videoSources = [
                        "public/assets/video/01_1.mp4",
                        "public/assets/video/02-2.mp4"
                    ];

                    // Set the initial source index
                    let currentSourceIndex = 0;

                    // Function to play the next video source
                    function playNextVideo() {
                        // Set the source of the video player
                        videoPlayer.src = videoSources[currentSourceIndex];

                        // Increment the source index
                        currentSourceIndex = (currentSourceIndex + 1) % videoSources.length;
                    }

                    // Event listener for when the video ends
                    videoPlayer.addEventListener("ended", playNextVideo);

                    // Event listener for the modal show event
                    const videoModal = document.getElementById("videoModal");
                    videoModal.addEventListener("show.bs.modal", () => {
                        // Start playing the videos when the modal is shown
                        playNextVideo();
                    });

                    // Event listener for the modal close button
                    const modalCloseButton = document.querySelector(".btn-close");
                    modalCloseButton.addEventListener("click", () => {
                        // Pause the video when the modal is closed
                        videoPlayer.pause();
                        videoPlayer.src = "";
                    });

                    $(document).ready(function() {
                        var testimonialCarousel = $(".testimonial-carousel");
        
                        testimonialCarousel.owlCarousel({
                            items: 1,
                            loop: true,
                            autoplay: true,
                            autoplayTimeout: 50, // Adjust the value to control the speed (in milliseconds)
                            autoplayHoverPause: true
                        });
        
                        testimonialCarousel.on('mouseover', '.owl-item', function() {
                            testimonialCarousel.trigger('stop.owl.autoplay');
                            $(this).find('.testimonial-item').css('height', 'auto');
                        });
        
                        testimonialCarousel.on('mouseout', '.owl-item', function() {
                            testimonialCarousel.trigger('play.owl.autoplay');
                            $(this).find('.testimonial-item').css('height', '');
                        });
                        });