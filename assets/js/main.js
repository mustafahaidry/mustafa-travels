
const slides=[...document.querySelectorAll('.hero-slide')];
const dots=[...document.querySelectorAll('.hero-dots button')];
let i=0;
function show(n){slides.forEach((s,x)=>s.classList.toggle('active',x===n));dots.forEach((d,x)=>d.classList.toggle('active',x===n));}
if(slides.length){setInterval(()=>{i=(i+1)%slides.length;show(i)},5200);dots.forEach((d,x)=>d.addEventListener('click',()=>{i=x;show(i)}));}
window.addEventListener('scroll',()=>document.getElementById('siteHeader')?.classList.toggle('scrolled',scrollY>30));
