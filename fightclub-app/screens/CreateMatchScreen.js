import React, { useEffect, useState } from 'react';
import { View, TextInput, Button, Text, Alert, StyleSheet, ScrollView } from 'react-native';
import { createMatch, getAllUsers } from '../services/api';

export default function CreateMatchScreen({ route, navigation }) {
  const challenger = route.params?.user?.email;
  const [opponent, setOpponent] = useState('');
  const [date, setDate] = useState('');
  const [allUsers, setAllUsers] = useState([]);

  useEffect(() => {
    const fetchUsers = async () => {
      try {
        const res = await getAllUsers();
        setAllUsers(res.data.filter(user => !!user.email));
      } catch (err) {
        console.error("❌ Failed to fetch users:", err);
        Alert.alert("Error", "Could not load user list");
      }
    };
    fetchUsers();
  }, []);

  const isValidDate = (dateString) => {
    const regex = /^\d{4}-\d{2}-\d{2}$/;
    if (!regex.test(dateString)) return false;
    const dateObj = new Date(dateString);
    return !isNaN(dateObj.getTime());
  };

  const create = async () => {
    const trimmedOpponent = opponent.trim().toLowerCase();
    const trimmedChallenger = challenger?.trim().toLowerCase();

    if (!trimmedOpponent) return Alert.alert("Missing Field", "Please enter opponent email.");
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(trimmedOpponent)) return Alert.alert("Invalid Email", "Please enter a valid opponent email.");
    if (trimmedOpponent === trimmedChallenger) return Alert.alert("Invalid Match", "You can't fight yourself!");
    if (!allUsers.some(user => user.email.toLowerCase() === trimmedOpponent)) return Alert.alert("Invalid Opponent", "No user with that email exists.");
    if (!isValidDate(date)) return Alert.alert("Invalid Date", "Please enter a valid date in YYYY-MM-DD format.");

    try {
      await createMatch({
        challenger_name: trimmedChallenger,
        opponent_name: trimmedOpponent,
        fight_date: date
      });

      Alert.alert("Success", "Match created");
      navigation.navigate("Dashboard", { user: route.params?.user });
    } catch (err) {
      Alert.alert("Error", err.response?.data?.error || "Something went wrong");
    }
  };

  return (
    <ScrollView style={styles.container}>
      <Text style={styles.label}>Opponent Email:</Text>
      <TextInput
        value={opponent}
        onChangeText={setOpponent}
        placeholder="Enter opponent's email"
        style={styles.input}
        placeholderTextColor="#ccc"
        autoCapitalize="none"
      />

      <Text style={styles.label}>Date (YYYY-MM-DD):</Text>
      <TextInput
        value={date}
        onChangeText={setDate}
        placeholder="Enter date"
        style={styles.input}
        placeholderTextColor="#ccc"
      />

      <View style={styles.buttonWrapper}>
        <Button title="Schedule Match" onPress={create} color="#e53e3e" />
      </View>

      <Text style={styles.sectionHeader}>Registered Users:</Text>
      {allUsers.length === 0 ? (
        <Text style={styles.text}>Loading users...</Text>
      ) : (
        allUsers
          .filter(user => user.email.toLowerCase() !== challenger?.toLowerCase())
          .map((user, index) => (
            <View key={index} style={styles.userCard}>
              <Text style={styles.userName}>{user.full_name}</Text>
              <Text style={styles.text}>Email: {user.email}</Text>
              <Text style={styles.text}>Weight: {user.weight} lbs</Text>
              <Text style={styles.text}>Height: {user.height} in</Text>
              <Text style={styles.text}>Bench Press: {user.bench_press} lbs</Text>
              <Text style={styles.text}>Experience: {user.experience}</Text>
            </View>
          ))
      )}
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    backgroundColor: '#1e293b',
    padding: 20,
    flex: 1,
  },
  label: {
    color: '#ffcc00',
    fontWeight: 'bold',
    marginBottom: 5,
  },
  input: {
    borderWidth: 1,
    borderColor: '#ccc',
    backgroundColor: '#334155',
    padding: 10,
    color: '#ffffff',
    borderRadius: 5,
    marginBottom: 15,
  },
  buttonWrapper: {
    marginBottom: 25,
  },
  sectionHeader: {
    color: '#ffffff',
    fontSize: 18,
    fontWeight: 'bold',
    marginTop: 10,
    marginBottom: 10,
  },
  text: {
    color: '#ffffff',
  },
  userCard: {
    marginBottom: 15,
    paddingBottom: 10,
    borderBottomWidth: 1,
    borderColor: '#475569',
  },
  userName: {
    color: '#ffcc00',
    fontWeight: 'bold',
    fontSize: 16,
    marginBottom: 4,
  },
});
