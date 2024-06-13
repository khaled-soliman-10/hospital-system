
ScrollReveal({
    reset: false,
    distance: '150px',
    duration: 1500,
    delay: 250
});

ScrollReveal().reveal(' .appointment .make-app, .services > h1, .services .service' ,{ origin: 'left' , easing: 'ease'});
ScrollReveal().reveal('.about .about-img' ,{ origin: 'top' , easing: 'ease'});
ScrollReveal().reveal('.doctors .doctor, .appointment img,  .services > p' ,{ origin: 'right' , easing: 'ease' });
ScrollReveal().reveal('.about .about-details' ,{ origin: 'bottom' , easing: 'ease'});