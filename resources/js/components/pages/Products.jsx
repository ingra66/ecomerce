import React from 'react';

const Products = () => {
  return (
    <div>
      <h1 className="text-3xl font-bold text-gray-900 mb-6">Productos</h1>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {/* Aquí irán los productos */}
        <div className="bg-white rounded-lg shadow p-6">
          <p className="text-gray-600">Lista de productos aquí</p>
        </div>
      </div>
    </div>
  );
};

export default Products; 