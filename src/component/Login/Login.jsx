import React from 'react'
import './Login.css'


function Login() {
  return (

    <div className="all">

    
    <div className='logg'>

      
      <div className="m1">
     <a href="/" ><button className='btnhome'>Home</button></a>
     
    <h1 className='get'> Login Please!</h1><br/><br/>
    <form  >
        <label>Username</label><br/><br/>
        <input type='text' name='user'/><br/>
        <label>Password</label><br/><br/>
        <input type='password' name='password'/><br/>
    </form>
    <a href='/Account' target='blank' style={{marginLeft:'8rem'}}><button     className='btn3'>User </button></a> 
    <a href='/ladmin' target='blank'><button style={{fontSize:'0.7em', backgroundColor:'transparent', padding:'7px 7px 7px 7px', color:'black'}}    className='btn3'>if you are Admin click here </button></a> 

</div>

    </div>
    </div>
  )
}

export default Login
