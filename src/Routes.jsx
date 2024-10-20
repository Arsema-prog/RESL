// Routes.js
import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Home from './pages/Home';
// import About from './pages/About'; // Ensure this is imported
// import Contact from './pages/Contact'; // Ensure this is imported
import Packages from './pages/Packages';
import Req from './pages/Req';
import Admin from './pages/Dashboard';
import Loging from './pages/Loging';
import Accounting from './pages/Admiin/Accounting';
import Pend from './pages/pending';
import Notification from './pages/Notification';
import LAdmin from './pages/LAdmin';

function AppRoutes() {
  return (
    <Router>
      <Routes>
        <Route path='/' element={<Home />} />
        <Route path='/package' element={<Packages />} />
        <Route path='/req' element={<Req />} />
        <Route path='/Admin' element={<Admin />} />
        <Route path='/Login' element={<Loging />} />
        <Route path='/Account' element={<Accounting />} />
        <Route path='/pend' element={<Pend />} />
        <Route path='/noti' element={<Notification />} />
        <Route path='/ladmin' element={<LAdmin />} />
        


      </Routes>
    </Router>
  );
}

export default AppRoutes;