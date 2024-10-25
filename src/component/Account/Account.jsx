import React, { useState, } from 'react';
import './Account.css';
import CheckCircleIcon from '@mui/icons-material/CheckCircle';

function Account() {
  const [employeeID, setEmployeeID] = useState('');
  const [guaranteeID1, setGuaranteeID1] = useState('');
  const [guaranteeID2, setGuaranteeID2] = useState('');
  const [employeeIDPic, setEmployeeIDPic] = useState(null);
  const [guaranteeIDPic1, setGuaranteeIDPic1] = useState(null);
  const [guaranteeIDPic2, setGuaranteeIDPic2] = useState(null);
  const [message, setMessage] = useState('');

  // Handle form submission
  const handleSubmit = async (e) => {
    e.preventDefault();

    // Create a form data object to send with the request
    const formData = new FormData();
    formData.append('employee_id', employeeID);
    formData.append('guarantee_id1', guaranteeID1);
    formData.append('guarantee_id2', guaranteeID2);
    formData.append('employee_id_pic', employeeIDPic);
    formData.append('guarantee_id_pic1', guaranteeIDPic1);
    formData.append('guarantee_id_pic2', guaranteeIDPic2);

    try {
      // Send the POST request to the PHP backend
      const response = await fetch('http://localhost:8080/add_employee.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();
      if (result.error) {
        setMessage(result.error);
      } else {
        setMessage(result.message || 'Form submitted successfully!');
      }
    } catch (error) {
      setMessage('Error submitting form');
    }
  };

  return (
    <div className='account'>
      <div className='cardAccount'>
        <p className='dear'>
          Dear Ananya, You are eligible <CheckCircleIcon className='CheckedIcon' />
        </p>
      </div>
      <div className="m1" style={{ marginLeft: '22rem' }}>
        <h1 className='get'>Loan Form</h1><br /><br />
        <form onSubmit={handleSubmit}>
          <label>Employee ID</label><br /><br />
          <input type='text' value={employeeID} onChange={(e) => setEmployeeID(e.target.value)} required /><br /><br />

          <label>Guarantee 1 ID</label><br /><br />
          <input type='text' value={guaranteeID1} onChange={(e) => setGuaranteeID1(e.target.value)} required /><br /><br />

          <label>Guarantee 2 ID (optional)</label><br /><br />
          <input type='text' value={guaranteeID2} onChange={(e) => setGuaranteeID2(e.target.value)} /><br /><br />

          <label>Employee ID Picture</label><br /><br />
          <input type='file' onChange={(e) => setEmployeeIDPic(e.target.files[0])} required /><br /><br />

          <label>Guarantee 1 ID Picture</label><br /><br />
          <input type='file' onChange={(e) => setGuaranteeIDPic1(e.target.files[0])} required /><br /><br />

          <label>Guarantee 2 ID Picture (optional)</label><br /><br />
          <input type='file' onChange={(e) => setGuaranteeIDPic2(e.target.files[0])} /><br /><br />

          <button className='btn32' type='submit'>Submit</button>
        </form>

        {message && <p>{message}</p>}
      </div>
    </div>
  );
}

export default Account;
