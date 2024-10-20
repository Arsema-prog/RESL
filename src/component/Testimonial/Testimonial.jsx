import React from 'react'
import './Test.css'
import buna from "../images/buna2.png"
import test from '../images/testi6.jpg'
import test1 from '../images/testi1.jpg'
import test2 from '../images/teams 4.jpg'

function Testis() {
  return (
    <div className="container" id='test'>
        <h1 className='test_title'>Testimonials</h1>
    
    <div className='card_test'>
        
       <div className="card1">
<div className="p1">

</div>
<img src={test} alt=""  className='img5'/>
<p className='pp1'>Yonas R.
"Absolutely seamless experience! The team at Buna bank guided me through every step of the loan process. Highly recommend!"</p>
       </div>
       <div className="card2">
        <div className="p2">
        </div>
        <img src={test1} alt=""  className='img5'/>
        <p className='pp1'>Biruk T.
        "I was impressed with how quickly I received my funds. The rates were competitive, and the customer service was top-notch!"</p>
       </div>
       <div className="card3">
        <div className="p3">
        </div>
        <img src={test2} alt=""  className='img5'/>
        <p className='pp1'>Sarah L.
        "Thanks to Buna bank, I was able to renovate my home without any stress. The process was quick and easy!"</p>
       </div>
    </div>
    </div>
  )
}

export default Testis
