import React from 'react';
import { Link } from 'react-router-dom';

const Layout = ({ children }) => {
  const handleLogout = async () => {
    // Simular logout por ahora
    console.log('Logout clicked');
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <nav className="bg-white shadow-sm">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between h-16">
            <div className="flex">
              <Link to="/" className="flex-shrink-0 flex items-center">
                <span className="text-xl font-bold text-gray-900">BeltSpot</span>
              </Link>
            </div>
            <div className="flex items-center space-x-4">
              <Link to="/products" className="text-gray-700 hover:text-gray-900">
                Productos
              </Link>
              <Link to="/cart" className="text-gray-700 hover:text-gray-900">
                Carrito
              </Link>
              <Link to="/login" className="text-gray-700 hover:text-gray-900">
                Iniciar Sesión
              </Link>
            </div>
          </div>
        </div>
      </nav>
      <main className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {children}
      </main>
    </div>
  );
};

export default Layout; 