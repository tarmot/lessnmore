#!/usr/bin/ruby

ROOT = File.expand_path(File.join(__dir__, '..'))

file = File.open(File.join(ROOT, 'README.md'), 'r')
current_version = file.read.match(/\ALessn More ([\d\.]+)/)[1]
file.close

puts "Current version is #{current_version}."
puts "Enter new version number: "

next_version = gets.chomp

exit unless next_version.match(/\A[\d\.]+\Z/)

file = File.open(File.join(ROOT, 'README.md'), 'r')
contents = file.read
file.close

file = File.open(File.join(ROOT, 'README.md'), 'w')
file.puts contents.sub(/\ALessn More ([\d\.]+)/, "Lessn More #{next_version}")
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
file.puts "#{next_version}\n\n- \n\n#{contents}"
file.close
