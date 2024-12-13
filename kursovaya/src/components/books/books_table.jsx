import axios from 'axios';
import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

const Books = () => {
    const [query, setQuery] = useState('index,title,information_publication,cover kursovaya."Books_catalog"');
    const [results, setResults] = useState([]);
    const [error, setError] = useState('');
    const [searchTerm, setSearchTerm] = useState(''); // Состояние для поиска

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
                setResults(response.data || []);
            } catch (err) {
                setError('Произошла ошибка при поиске.');
                console.error('Ошибка при выполнении запроса:', err.response ? err.response.data : err.message);
            }
        };
        fetchData();
    }, [query]); // Добавляем query в зависимости, чтобы перезапрашивать данные при изменении

    // Функция для обработки ввода в поле поиска
    const handleSearchChange = (event) => {
        setSearchTerm(event.target.value);
    };

    // Фильтрация результатов на основе searchTerm
    const filteredResults = results.filter(book =>
        book.title.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div>
            <input
                type="text"
                placeholder="Поиск по названию книги..."
                value={searchTerm}
                onChange={handleSearchChange}
            />
            {filteredResults.length > 0 ? (
                filteredResults.map((book) => (
                    <div key={book.index}>
                        <h3>{book.title}</h3>
                        <p>{book.information_publication}</p>
                        <Link to={`/books_info/${book.index}`}>
                            Подробнее
                        </Link>
                    </div>
                ))
            ) : (
                <p>Книги не найдены.</p>
            )}
        </div>
    );
};

export default Books;
