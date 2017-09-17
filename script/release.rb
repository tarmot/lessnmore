#!/usr/bin/ruby

ROOT = File.expand_path(File.join(__dir__, '..'))

file = File.open(File.join(ROOT, 'README.md'), 'r')
current_version = file.read.match(/\ALessn More ([\d\.]+)/)[1]
file.close

puts "Current version is #{current_version}."
puts "Enter new version number: "

next_version = gets.chomp

exit unless next_version.match(/\A[\d\.]+\Z/)

change_notes = []

puts "Please enter the first bullet point for #{next_version}’s CHANGES:"

loop do
  next_change_note = gets.chomp

  if next_change_note == "" then
    break
  else
    change_notes << next_change_note
    puts "Enter the next CHANGES bullet point (ENTER to stop):"
  end
end


upgrade_paragraphs = []

puts "Please enter the first paragraph of UPGRADE instructions for #{next_version}:"

loop do
  next_p = gets.chomp

  if next_p == "" then
    break
  else
    upgrade_paragraphs << next_p
    puts "Enter the next UPGRADE paragraph (ENTER to stop):"
  end
end

file = File.open(File.join(ROOT, 'README.md'), 'r')
contents = file.read
file.close

file = File.open(File.join(ROOT, 'README.md'), 'w')
contents = contents.sub(
  /\ALessn More ([\d\.]+)/,
  "Lessn More #{next_version}"
).sub(
  /(\nLegal\n-----)/,
  "\#\#\# #{next_version}\n\n#{change_notes.join("\n\n")}\n\n\\1"
)
contents = contents.sub(
  /(\nAPI\n---)/,
  "\#\#\# Upgrading from #{current_version} to #{next_version}\n\n#{upgrade_paragraphs.join("\n\n")}\n\\1"
) unless upgrade_paragraphs.empty?
file.puts contents
file.close


file = File.open(File.join(ROOT, 'dist/-/index.php'), 'r')
contents = file.read
file.close

file = File.open(File.join(ROOT, 'dist/-/index.php'), 'w')
file.puts contents.sub(/(define\('BCURLS_VERSION',\s*')([\d\.]+)('\))/, "\\1#{next_version}\\3")
file.close


file = File.open(File.join(ROOT, 'CHANGES.txt'), 'r')
contents = file.read
file.close

file = File.open(File.join(ROOT, 'CHANGES.txt'), 'w')
file.puts "#{next_version}\n\n- #{change_notes.join("\n\n- ")}\n\n\n#{contents}"
file.close
