import React, { useEffect, useState } from 'react';
import { View, TextInput, Button, Text, Alert } from 'react-native';
import { createMatch, getAllUsers } from '../services/api';
import { ScrollView } from 'react-native';
import axios from 'axios';

export default function CreateMatchScreen({ route, navigation }) {
  const challenger = route.params?.user?.email;
  const [opponent, setOpponent] = useState('');
  const [date, setDate] = useState('');
  const [allUsers, setAllUsers] = useState([]);

  // ✅ Load all users on mount
  useEffect(() => {
    const fetchUsers = async () => {
      try {
        const res = await getAllUsers();
        console.log("✅ Loaded users from API:", res.data);
        setAllUsers(res.data.filter(user => !!user.email)); // keep this for now
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

    if (!trimmedOpponent) {
      Alert.alert("Missing Field", "Please enter opponent email.");
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(trimmedOpponent)) {
      Alert.alert("Invalid Email", "Please enter a valid opponent email address.");
      return;
    }

    if (trimmedOpponent === trimmedChallenger) {
      Alert.alert("Invalid Match", "You can't fight yourself!");
      return;
    }

    if (!allUsers.some(user => user.email.toLowerCase() === trimmedOpponent)) {
      Alert.alert("Invalid Opponent", "No user with that email exists.");
      return;
    }

    if (!isValidDate(date)) {
      Alert.alert("Invalid Date", "Please enter a valid date in YYYY-MM-DD format.");
      return;
    }

    try {
      await createMatch({
        challenger_name: trimmedChallenger,
        opponent_name: trimmedOpponent,
        fight_date: date
      });

      Alert.alert("Success", "Match created");
      navigation.navigate("Dashboard", { user: route.params?.user });
    } catch (err) {
      console.log("Create match error:", JSON.stringify(err, null, 2));
      Alert.alert("Error", err.response?.data?.error || "Something went wrong");
    }
  };

  return (
    <ScrollView style={{ padding: 20 }}>
      <Text>Opponent Email:</Text>
      <TextInput
        value={opponent}
        onChangeText={setOpponent}
        placeholder="Enter opponent's email"
        style={{ borderWidth: 1, marginBottom: 10, padding: 8 }}
        autoCapitalize="none"
      />

      <Text>Date (YYYY-MM-DD):</Text>
      <TextInput
        value={date}
        onChangeText={setDate}
        placeholder="Enter date"
        style={{ borderWidth: 1, marginBottom: 20, padding: 8 }}
      />

      <Button title="Schedule Match" onPress={create}/>

      <Text style={{ marginTop: 20, fontWeight: 'bold', fontSize: 16 }}>
        Registered Users:
      </Text>

      {allUsers.length === 0 ? (
        <Text>Loading users...</Text>
        ) : (
        allUsers
        .filter(user => user.email && user.email.toLowerCase() !== challenger?.toLowerCase())
          .map((user, index) => (
            <View key={index} style={{ marginVertical: 10, borderBottomWidth: 1, paddingBottom: 10 }}>
              <Text style={{ fontWeight: 'bold' }}>{user.full_name}</Text>
              <Text>Email: {user.email}</Text>
              <Text>Weight: {user.weight} lbs</Text>
              <Text>Height: {user.height} in</Text>
              <Text>Bench Press: {user.bench_press} lbs</Text>
              <Text>Experience: {user.experience}</Text>
            </View>
          ))
      )}

    </ScrollView>
  );
}
