import React from 'react';
import { Link, useLocation } from 'react-router-dom';

const Header = () => {
  const location = useLocation();
  
  // Function to check if the link is active
  const isActive = (path) => {
    return location.pathname === path;
  };

  return (
    <header className="bg-gray-800 shadow-md">
      <div className="container mx-auto px-4">
        <nav className="flex items-center justify-between py-4">
          <div className="text-white font-bold text-xl">
            <Link to="/">MyApp</Link>
          </div>
          
          <ul className="flex space-x-6">
            <li>
              <Link 
                to="/" 
                className={`${isActive('/') 
                  ? 'text-white font-medium border-b-2 border-blue-500' 
                  : 'text-gray-300 hover:text-white'} 
                  transition duration-200`}
              >
                Home
              </Link>
            </li>
            <li>
              <Link 
                to="/Dash" 
                className={`${isActive('/Dash') 
                  ? 'text-white font-medium border-b-2 border-blue-500' 
                  : 'text-gray-300 hover:text-white'} 
                  transition duration-200`}
              >
                Dashboar
              </Link>
            </li>
            <li>
              <Link to="/products" className={`${isActive('/products') ? 'text-white font-medium border-b-2 border-blue-500' : 'text-gray-300 hover:text-white'} transition duration-200`}>Product</Link>
            </li>
                  
          </ul>
          
          <div className="hidden md:block">
            <Link 
              to="/login" 
              className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">Login
            </Link>
          </div>
        </nav>
      </div>
    </header>
  );
};

export default Header;