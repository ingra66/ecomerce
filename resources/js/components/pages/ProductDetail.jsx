import React from 'react';
import { useParams } from 'react-router-dom';

const ProductDetail = () => {
  const { id } = useParams();

  return (
    <div>
      <h1 className="text-3xl font-bold text-gray-900 mb-6">
        Detalle del Producto {id}
      </h1>
      <div className="bg-white rounded-lg shadow p-6">
        <p className="text-gray-600">Detalles del producto aquí</p>
      </div>
    </div>
  );
};

export default ProductDetail; 