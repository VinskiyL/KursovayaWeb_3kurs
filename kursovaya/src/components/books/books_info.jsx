import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';
import { useSelector } from 'react-redux';

const Books_info = () => {
    const { index } = useParams(); // Извлечение bookId из параметров URL
    const [query, setQuery] = useState('index,title,information_publication,cover kursovaya."Books_catalog"');
    const [books, setBooks] = useState([]);
    const [error, setError] = useState('');
    const isAuthenticated = useSelector((state) => state.user.isAuthenticated);

    const handleBooking = async (index) => {
        try {
            const response = await axios.get(`https://kursovaya.local/addBooking.php`, {
                params: { index }, withCredentials: true,
            });

            const result = response.data;
            if (result.success) {
                alert('Бронирование успешно!');
            } else {
                alert('Не удалось забронировать. Попробуйте снова.');
            }
        } catch (error) {
            console.error('Ошибка:', error);
            alert('Произошла ошибка. Попробуйте позже.');
        }
    };

    useEffect(() => {
        const fetchData = async () => {
            setError('');
            try {
                const response = await axios.get(`https://kursovaya.local/select.php`, {
                    params: { query },
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });
                setBooks(response.data || []);
            } catch (err) {
                setError('Произошла ошибка при запросе.');
                console.error('Ошибка при выполнении запроса:', err.response ? err.response.data : err.message);
            }
        };
        fetchData();
    }, []);

    if (error) {
        return <p>{error}</p>;
    }

    if (!books || books.length === 0) {
        return <p>Данные о книге не найдены.</p>;
    }

    const book = books.find(b => b.index === index);

    if (!book) {
        return <p>Данные о книге не найдены.</p>;
    }

    return (
            <>
                <h3>{book.title}</h3>
                <p>{book.information_publication}</p>
                {isAuthenticated && <button onClick={() => handleBooking(book.index)}>Забронировать</button>}
            </>
        );
};

export default Books_info;


