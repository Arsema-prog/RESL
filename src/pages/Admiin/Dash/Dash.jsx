import React, { useState, useEffect } from 'react';
import Sidebar from '../Sidebar';
import './Dash.css'; // Your CSS file
import Homee from '../main/main';


const Dashboard = () => {
  const [branchData, setBranchData] = useState([]);
  const [branchId, setBranchId] = useState(1); // default branch ID

  useEffect(() => {
    fetchBranchData(branchId);
  }, [branchId]);

  const fetchBranchData = async (branchId) => {
    const formData = new FormData();
    formData.append('branch_id', branchId);

    try {
      const response = await fetch('http://localhost:3000/branch_manager.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();
      if (result.status === 'success') {
        setBranchData(result.data);
      } else {
        console.error(result.message);
      }
    } catch (error) {
      console.error('Error fetching data:', error);
    }
  };
  return (
    <div className="dashboard">
      //<Sidebar />
      <div className="charts-container">
        <h1>Branch Manager Dashboard</h1>
        <input
          type="number"
          value={branchId}
          onChange={(e) => setBranchId(e.target.value)}
          placeholder="Enter Branch ID"
        />
        <button onClick={() => fetchBranchData(branchId)}>Fetch Data</button>

        <table>
          <thead>
            <tr>
              <th>Branch ID</th>
              <th>Employee ID</th>
              <th>Loan Amount</th>
              {/* Add more columns as needed */}
            </tr>
          </thead>
          <tbody>
            {branchData.map((item) => (
              <tr key={item.employee_id}>
                <td>{item.branch_id}</td>
                <td>{item.employee_id}</td>
                <td>{item.loan_amount}</td>
                {/* Display more data if necessary */}
              </tr>
            ))}
          </tbody>
        </table>

        //<Homee />
      </div>
    </div>
  );
};

export default Dashboard;

