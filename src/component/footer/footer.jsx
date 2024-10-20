import React from 'react'
import './footer.css'
import LocalPhoneIcon from '@mui/icons-material/LocalPhone';
import MailOutlineIcon from '@mui/icons-material/MailOutline';
import AddLocationAltIcon from '@mui/icons-material/AddLocationAlt';
// import MessageIcon from '@mui/icons-material/Message';
import { Link } from 'react-scroll';

function Footer() {
  return (
    <div className='foot'>
      <div>
<h1 className='log'>Buna</h1>
      </div>
      {/*  */}
      <div className="route">
      
      <Link className='lin' to="Nav"spy={true} smooth={true} duration={500}>Home</Link><br /><br />
    <Link className='lin' to="About_section" spy={true} smooth={true} duration={500}>About</Link><br /><br />
    <Link className='lin' to="test" spy={true} smooth={true} duration={500}>Testimonial</Link><br /><br />
    <Link className='lin' to="/packages" spy={true} smooth={true} duration={500}>Packages</Link><br /><br />
    <Link className='lin' to="Contact_us" spy={true} smooth={true} duration={500}>Contact us</Link>


      </div>
<div className="infofotter">
<h1 className='con'>Contact us</h1>
    <p  className='add'><LocalPhoneIcon/>Phone:+251973424545</p>
    <p className="add"> <MailOutlineIcon/>Email:ananyateshome2@gmail</p>
    <p className='add'><AddLocationAltIcon/> Address:Addis Ababa ,Kotebe</p>
</div>
    </div>
  )
}

export default Footer
